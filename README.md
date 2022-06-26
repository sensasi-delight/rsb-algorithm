# RSB Algorithm

Residential Social Benefit (RSB) Algorithm is an algorithm based on Eigenvector Centrality and Fuzzy Comprehensive Evaluation to conclude and compare two qualitative assessment.

## Installation

Install using composer:

```bash
composer require sensasi-delight/rsb-algorithm
```

## Usage

The usage examples of this library are also available on [examples folder](https://github.com/sensasi-delight/eigenvector-centrality-php/tree/main/examples).

1. Define the assesment scale.

    ```php
    $assessment_scale = [
        "Very Low" => 1,
        "Low" => 2,
        "Medium" => 3,
        "High" => 4,
        "Very High" => 5,
    ];
    ```

2. Define two datasets.

    ```php
    $datasets = [
        ['dataset_from'] => [
            ['question1' => [
                ['respondent1' => 5],
                ['respondent2' => 3],
                ['respondent3' => 4],
                ['respondent4' => 2],
                ['respondent5' => 3]
            ], 'question2' => [
                ['respondent1' => 4],
                ['respondent2' => 3],
                ['respondent3' => 3],
                ['respondent4' => 2],
                ['respondent5' => 1]
            ] ... ]
        ], ['dataset_against'] => [
            ['question1' => [
                ['respondent1' => 3],
                ['respondent2' => 5],
                ['respondent3' => 2],
                ['respondent4' => 3],
                ['respondent5' => 4]
            ], 'question2' => [
                ['respondent1' => 2],
                ['respondent2' => 3],
                ['respondent3' => 4],
                ['respondent4' => 5],
                ['respondent5' => 1]
            ] ... ]
        ]
    ];
    ```

3. Make a RSBAlgorithm object and set the properties

    ```php
    $RSBAlgorithm = new RSBAlgorithm(
        $assessment_scale,
        $datasets
    );
    ```

    or

    ```php
    $RSBAlgorithm = new RSBAlgorithm;

    $RSBAlgorithm->assessment_scale = $assessment_scale;
    $RSBAlgorithm->datasets = $datasets;
    ```

4. Get the score
    overall score:

    ```php
    print_r(RSBAlgorithm->score)
    ```

    questions/criterias/variables score

    ```php
    print_r(RSBAlgorithm->scores);
    ```

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement". Don't forget to give the project a star! Thanks again!

1. Fork the Project.
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`).
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the Branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

The code is released under the MIT license.

## Contact

Email - [zainadam.id@gmail.com](mailto:zainadam.id@gmail.com?subject=[GitHub]%20EigenvectorCentralityPHP)

Twitter - [@sensasi_DELIGHT](https://twitter.com/sensasi_DELIGHT)
