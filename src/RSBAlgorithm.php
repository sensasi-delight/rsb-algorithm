<?php

namespace SensasiDelight;

use MathPHP\LinearAlgebra\MatrixFactory;
use SensasiDelight\Graph;


class RSBAlgorithm
{
	public $scores;
	public $score;
	public $weights;

	private $datasets;
	private $assessment_scale;

	public function __construct(array $assessment_scale, array $datasets) {
		$this->__set('assessment_scale', $assessment_scale);
		$this->__set('datasets', $datasets);
	}

	public function __set($name, $value)
	{
		if ($name == "assessment_scale") {
			$this->assessment_scale = $value;
		}

		if ($name == "datasets") {
			if (count($value) !== 2) {
				throw new \Exception("datasets format unrecognizeable");
			}
	
			$this->datasets = $value;
		}




		if ($this->isRequiredFilled()) {
			$this->calculate();
		}
	}

	private function set_weights()
	{
		$g = new Graph;

		$questions = current($this->datasets);
		foreach ($questions as $qId => $respondents) {
			$question_ids[] = $qId;
			foreach ($respondents as $rId => $answer) {
				$g->add_edge($qId, $rId);
				$respondent_ids[] = $rId;
			}
		}

		$respondent_ids = array_unique($respondent_ids);

		$eigenvectors = $g->get_eigenvector_centrality();
		$questions_weights = self::toItsPercentage(array_intersect_key($eigenvectors, array_flip($question_ids)));
		$respondent_weights = self::toItsPercentage(array_intersect_key($eigenvectors, array_flip($respondent_ids)));

		$this->weights = array_merge($questions_weights, $respondent_weights);
	}

	private function calculate()
	{
		$this->set_weights();

		$w_vector = MatrixFactory::create([array_values($this->assessment_scale)]);
		$dataset_ids = array_keys($this->datasets);
		$dataset_id_from = current($dataset_ids);
		$dataset_id_against = end($dataset_ids);

		$scores = [];

		foreach ($this->datasets as $dataset_id => $dataset) {
			foreach ($dataset as $qId => $respondents) {
				$evaluation_vector = array_fill(0, count($this->assessment_scale), 0);

				foreach ($respondents as $rId => $answer) {
					$evaluation_vector[$answer - 1] += $this->weights[$rId];
				}

				$evaluation_matrices[$dataset_id][$qId] = self::toItsPercentage($evaluation_vector);

				$r = MatrixFactory::create([array_values($evaluation_matrices[$dataset_id][$qId])]);

				if (empty($scores[$qId])) {
					$scores[$qId] = new \stdClass;
				}

				$scores[$qId]->{$dataset_id} = $r->multiply($w_vector->transpose())->get(0, 0);
			}
		}


		foreach ($scores as $qId => &$score) {
			$score->gap = $score->{$dataset_id_from} - $score->{$dataset_id_against};
		}

		$this->scores = $scores;



		// calculate orevall score
		$score = new \stdClass;

		$weights_matrix = array_values(array_intersect_key($this->weights, current($evaluation_matrices)));
		$weights_matrix = MatrixFactory::create([$weights_matrix]);

		foreach ($evaluation_matrices as $dataset_id => $evaluation_matrix) {
			$evaluation_matrix = array_values($evaluation_matrix);
			$evaluation_matrix = MatrixFactory::create($evaluation_matrix);
			$score_vector = $weights_matrix->multiply($evaluation_matrix);

			$score->{$dataset_id} = $score_vector->multiply($w_vector->transpose())->get(0, 0);
		}

		$score->gap = $score->{$dataset_id_from} - $score->{$dataset_id_against};
		$this->score = $score;
	}


	/**
	 * Helper
	 */
	public static function toItsPercentage(array $numbers)
	{
		$sum = array_sum($numbers);
		foreach ($numbers as $i => $number) {
			$numbers[$i] = $number / $sum;
		}

		return $numbers;
	}

	private function isRequiredFilled()
	{
		return $this->datasets AND $this->assessment_scale;
	}
}
