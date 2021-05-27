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
        <!--Logo and details of the company-->
        <div class="head p-3">
            <div class="image">
                <img class="img" src="{{ asset('images/radiant_logo-removebg-preview.png') }}"
                    alt="Radiant minds logo">
            </div>
            <div class="content text-center">
                <h1 class="fam">
                    <strong>RADIANT MINDS SCHOOL</strong>
                </h1>
                <img class="arabic" src="{{ asset('images/arabic-removebg-preview.png') }}" alt="Arabic text">
                <p class="h4 space"><strong>Creche, Nursery & Primary</strong></p>
                <p>
                    <strong>
                        <i class="fa fa-map-marker text-danger "></i>Block 20 Road 1, Ajebo Road Housing Estate, Kemta,
                        Idi-Aba, Abeokuta
                        <br>
                        <i class="fa fa-phone text-danger"></i>08172951965 &nbsp;&nbsp; <img class="icon"
                            src="{{ asset('images/whatsapp.png') }}" alt="">08147971373 &nbsp;&nbsp;
                        <img class="icon" src="{{ asset('images/gmail.png') }}" alt=""> radiantmindsschool@gmail.com
                    </strong>
                </p>
                <p class="h5"><strong class="text-uppercase"><u>{{ $period->term->name }} STUDENT'S PERFORMANCE
                            REPORT</u></strong></p>
            </div>
        </div>

        <!--Student details-->

        <div class="some">
            <div class="one">
                <form action="" class="p-3">
                    <div class="stu-name mb-2">
                        <label for="name">NAME:</label>
                        <div class="name border-bottom"><span
                                class="px-3 fw-bold">{{ $student->first_name . ' ' . $student->last_name }}</span>
                        </div>
                    </div>
                    <div class="sec mb-2">
                        <div class="stu-class">
                            <label for="class">CLASS:</label>
                            <div class="class border-bottom"><span
                                    class="px-3 fw-bold">{{ $student->classroom->name }}</span></div>
                        </div>
                        <div class="stu-sess">
                            <label for="session">SESSION:</label>
                            <div class="session border-bottom"><span
                                    class="px-3 fw-bold">{{ $period->academicSession->name }}</span></div>
                        </div>
                        <div class="stu-add">
                            <label for="admission">ADMISSION:</label>
                            <div class="admission border-bottom "><span
                                    class="px-3 fw-bold">{{ $student->admission_no }}</span></div>
                        </div>
                    </div>
                    <div class="thrd">
                        <div class="stu-dob">
                            <label for="dob">DOB:</label>
                            <div class="dob border-bottom "><span
                                    class="px-3 fw-bold">{{ $student->date_of_birth }}</span></div>
                        </div>
                        <div class="stu-age">
                            <label for="age">AGE:</label>
                            <div class="age border-bottom "><span class="px-3 fw-bold">{{ $age }}</span></div>
                        </div>
                        <div class="stu-gender">
                            <label for="gender">GENDER:</label>
                            <div class="gender border-bottom "><span class="px-3 fw-bold">{{ $student->sex }}</span>
                            </div>
                        </div>
                    </div>
                </form>
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
                                <th scope="row">{{ $key }}</td>
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
                                @elseif($result->total >= 40 && $result->total <= 49) <td class="text-yellow-500">D
                                        </td>
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
                            <td>{{ $period->no_times_school_opened }}</td>

                        </tr>
                        <tr>
                            <td>No of times present</td>
                            <td>
                                @if (!is_null($period->attendance))
                                    {{ $period->attendance->value }}
                                @else
                                    null
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <td> No of times Absent</td>
                            <td>
                                @if (!is_null($period->attendance))
                                    {{ $period->no_times_school_opened - $period->attendance->value }}
                                @else
                                    null
                                @endif
                            </td>
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
                            <td class="text-center fw-bold">{{ $nextTermBegins }}</td>
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
                            <td class="text-center fw-bold">₦ {{ $nextTermFee }}</td>
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

        <div class="remark-container">
            <div class="border p-3 remark mb-4">
                <div class="t-wrapper">
                    <div class="class-teacher-remark">
                        <label for="class-teachers-remark">Class Teacher's Remark</label>
                        <div class="remark-ct border-bottom fst-italic ps-2">
                            @if ($teacherRemark)
                                {{ $teacherRemark->remark }}
                            @endif
                        </div>
                    </div>
                    <div class="class-teacher-sign">
                        <label for="class-teachers-sign">Sign</label>
                        <div class="sign border-bottom">
                            @if ($teacherRemark)
                            <span class="ps-4">
                                <img src="{{ asset($teacherRemark->teacher->signature) }}" height=40 width="60" alt="teacher's signature">
                            </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            <div class="remark2 mb-4">
                <div class="hod-wrapper border p-3">
                    <div class="class-teacher-remark">
                        <label for="class-teachers-remark">HOS's Remark</label>
                        <div class="remark-hd border-bottom fst-italic ps-2">
                            {{-- @if ($remarks)
                                {{ $remarks->hos_remark }}
                            @endif --}}
                        </div>
                    </div>
                    <div class="class-teacher-sign ">
                        <label for="class-teachers-sign">Sign</label>
                        <div class="sign border-bottom"></div>
                    </div>

                </div>
            </div>
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
