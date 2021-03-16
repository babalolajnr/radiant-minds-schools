<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('TAssets/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>
    <div class="container">
        <h1 class="text-center color">
            RADIANT MINDS SCHOOL <br>
            <small class="text-muted h4">STUDENT'S PERFORMANCE REPORT</small>
        </h1>
        <div class="one">
            <div class="p-3">
                <div class="row mt-2">
                    <label class="col-sm-1">NAME:</label>
                    <div class="col-sm-11 bord border-bottom ">{{ $student->first_name . ' ' . $student->last_name }}
                    </div>
                </div>
                <div class="row mt-3">
                    <label class="col-sm-1">CLASS:</label>
                    <div class="col-sm-3 bord1 border-bottom ">{{ $student->classroom->name }}</div>
                    <label class="col-sm-1">SESSION:</label>
                    <div class="col-sm-3 bord2 border-bottom ">{{ $academicSession->name }}</div>
                    <label class="col-sm-2">ADMISSION NO:</label>
                    <div class="col-sm-3 mr-5 bord3 border-bottom ">{{ $student->admission_no }}</div>
                </div>
                <div class="row mt-3 mb-4">
                    <label class="col-sm-1">DOB:</label>
                    <div class=" col-sm-3 bord4 border-bottom ">{{ $student->date_of_birth }}</div>
                    <label class="col-sm-1">AGE:</label>
                    <div class="col-sm=3 bord5 border-bottom ">{{ $age }}</div>
                    <label class="col-sm-1">GENDER:</label>
                    <div class="col-sm-3 bord6 border-bottom ">{{ $student->sex }}</div>
                </div>
            </div>
        </div>
        <div class="two ">
            <div class="sub1">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th class="heading" scope="col"> SUBJECTS</th>
                            <th scope="col">C.A.<br>(40)</th>
                            <th scope="col">Exam<br>(60)</th>
                            <th scope="col">Total<br>(100)</th>
                            <th scope="col">Highest score</th>
                            <th scope="col">Lowest score</th>
                            <th scope="col">Class Average</th>
                            <th scope="col">Grade</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($results as $key => $result)
                            <tr>
                                <td>{{ $key }}</td>
                                @if ($result == null)
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @else
                                    <td>{{ $result->ca }}</td>
                                    <td>{{ $result->exam }}</td>
                                    <td>{{ $result->total }}</td>
                                    <td>{{ $maxScores[$result->subject->name] }}
                                    <td>{{ $minScores[$result->subject->name] }}
                                    </td>
                                    <td>{{ round($averageScores[$result->subject->name], 2) }}
                                    </td>
                                    @if ($result->total <= 39)
                                        <td class="text-red-700">F</td>
                                    @elseif($result->total >= 40 && $result->total <= 49) <td
                                            class="text-yellow-500">D</td>
                                        @elseif($result->total >= 50 && $result->total <= 59) <td
                                                class="text-green-300">C
                                                </td>
                                            @elseif($result->total >= 60 && $result->total <= 69) <td
                                                    class="text-green-600">B</td>
                                                @elseif($result->total >= 70 && $result->total <= 100) <td
                                                        class="text-green-900">A</td>
                                                    @else
                                                        <td></td>
                                    @endif
                                @endif

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div class="sub2">
                <table class="table caption-top table-sm table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center heading" colspan="3">PERFORMANCE SUMMARY</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2">Total Obtained:</td>
                            <td>{{ $totalObtained }}</td>

                        </tr>
                        <tr>
                            <td colspan="2">Total Obtainable:</td>
                            <td>{{ $totalObtainable }}</td>

                        </tr>
                        <tr>
                            <td colspan="3" class='py-3'></td>
                        </tr>
                        <tr>
                            <td>%TAGE</td>
                            <td colspan="2">{{ round($percentage, 2) }}</td>

                        </tr>
                        <tr>
                            <td>GRADE</td>
                            @if ($percentage <= 39)
                                <td colspan='2' class="text-red-700">F</td>
                            @elseif($percentage > 39 && $percentage <= 49) <td colspan='2' class="text-yellow-500">
                                    D</td>
                                @elseif($percentage > 49 && $percentage <= 59) <td colspan='2'
                                        class="text-green-300">C
                                        </td>
                                    @elseif($percentage > 59 && $percentage <= 69) <td colspan='2'
                                            class="text-green-600">B
                                            </td>
                                        @elseif($percentage > 69 && $percentage <= 100) <td colspan='2'
                                                class="text-green-900">
                                                A</td>
                                            @else
                                                <td colspan='2'></td>
                            @endif
                        </tr>
                    </tbody>
                </table>


                <table class="table caption-top table-sm table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center heading" colspan="3">GRADE SCALE</td>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>A</td>
                            <td>70-100%</td>
                            <td>EXCELLENT</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>60-69%</td>
                            <td>VERY GOOD</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>50-59%</td>
                            <td>GOOD</td>

                        </tr>
                        <tr>
                            <td>D</td>
                            <td>40-49%</td>
                            <td>AVERAGE</td>
                        </tr>
                        <tr>
                            <td>F</td>
                            <td>0-39%</td>
                            <td>FAIL</td>
                        </tr>
                    </tbody>
                </table>


                <table class="table caption-top table-sm table-bordered">

                    <tbody>
                        <tr>
                            <td class="text-center heading" colspan="2">ATTENDANCE SUMMARY</td>
                        </tr>
                        <tr>
                            <td>No of Times School opened</td>
                            <td></td>

                        </tr>
                        <tr>
                            <td>No of times present</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td> No of times Absent</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>


                <table class="table caption-top table-sm table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center heading" colspan="1">NEXT TERM BEGINS</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Empty</td>
                        </tr>

                    </tbody>
                </table>

                <table class="table caption-top table-sm table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center heading">NEXT TERM FEES</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Empty</td>
                        </tr>

                    </tbody>
                </table>

            </div>
        </div>


        <div class="three mt-5">
            <div class="sub3">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr class="heading">
                            <th scope="col">PSYCHOMOTOR DOMAIN </th>
                            <th scope="col">5</th>
                            <th scope="col">4</th>
                            <th scope="col">3</th>
                            <th scope="col">2</th>
                            <th scope="col">1</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pdTypes as $pdType)
                            <tr>
                                <td>{{ $pdType->name }}</td>
                                <td>
                                    @if (array_key_exists($pdType->name, $pds) && $pds[$pdType->name] == '5')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($pdType->name, $pds) && $pds[$pdType->name] == '4')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($pdType->name, $pds) && $pds[$pdType->name] == '3')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($pdType->name, $pds) && $pds[$pdType->name] == '2')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($pdType->name, $pds) && $pds[$pdType->name] == '1')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sub4">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr class="heading">
                            <th scope="col">AFFECTIVE DOMAIN </th>
                            <th scope="col">5</th>
                            <th scope="col">4</th>
                            <th scope="col">3</th>
                            <th scope="col">2</th>
                            <th scope="col">1</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($adTypes as $adType)
                            <tr>
                                <td>{{ $adType->name }}</td>
                                <td>
                                    @if (array_key_exists($adType->name, $ads) && $ads[$adType->name] == '5')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($adType->name, $ads) && $ads[$adType->name] == '4')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($adType->name, $ads) && $ads[$adType->name] == '3')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($adType->name, $ads) && $ads[$adType->name] == '2')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                                <td>
                                    @if (array_key_exists($adType->name, $ads) && $ads[$adType->name] == '1')
                                        <i class="fas fa-check"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="remark-container mb-5">
            <div class="remark mb-4">
                <form>
                    <div class="row border p-3">
                        <label class="col-sm-2">Class Teacher's Remark</label>
                        <div class="col-sm-6 border-bottom"> </div>
                        <label class="col-sm-1 mr-1">Sign</label>
                        <div class="col-sm-3 border-bottom"> </div>
                    </div>
                </form>
            </div>
            <div class="remark2">
                <form>
                    <div class="row border p-3">
                        <label class="col-sm-2 ">HOD's Remark</label>
                        <div class="col-sm-6 border-bottom"></div>
                        <label class="col-sm-1 ">Sign</label>
                        <div class="col-sm-3 border-bottom"></div>
                    </div>
                </form>
            </div>
        </div>

        <footer class="heading p-3"></footer>
    </div>







    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
