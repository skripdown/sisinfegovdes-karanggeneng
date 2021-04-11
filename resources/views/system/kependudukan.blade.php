@extends('system.template')


@section('title')
    kependudukan
@endsection

@section('page-description')
    sistem informasi administrasi kependudukan desa Karanggeneng kabupaten Lamongan
@endsection

@section('nav-title')
    SIAK
@endsection

@section('app-nav')
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#distrik">STRUKTUR</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#umur">UMUR</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#pekerjaan">PEKERJAAN</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#pendidikan">PENDIDIKAN</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#agama">AGAMA</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#golongandarah">GOLONGAN DARAH</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="#jeniskelamin">JENIS KELAMIN</a>
    </li>
    <li class="nav-item">
        <a class="nav-link page-scroll" href="{{route('register')}}">DAFTAR</a>
    </li>
@endsection

@section('page-title')
    Kependudukan
@endsection

@section('page-subtitle')
    Sistem Informasi Administrasi Kependudukan
@endsection

@section('content-body')
    <div class="container pt-4 pb-4 mt-4 mb-4"></div>
    <div class="container-fluid mb-4 pb-4">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <h2 id="distrik" class="text-center">STRUKTUR DESA</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-distrik" class="mb-4"></div>
                        <table id="table-district" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Dusun</th>
                                <th class="text-center" colspan="4">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">RT</th>
                                <th class="text-center">RW</th>
                                <th class="text-center">Keluarga</th>
                                <th class="text-center">Penduduk</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="umur" class="text-center pt-5 mt-2">DATA UMUR</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-umur" class="mb-4"></div>
                        <table id="table-age" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="pekerjaan" class="text-center pt-5 mt-2">DATA PEKERJAAN</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-pekerjaan" class="mb-4"></div>
                        <table id="table-occupation" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="pendidikan" class="text-center pt-5 mt-2">DATA PENDIDIKAN</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-pendidikan" class="mb-4"></div>
                        <table id="table-education" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="agama" class="text-center pt-5 mt-2">DATA AGAMA</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-agama" class="mb-4"></div>
                        <table id="table-religion" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="golongandarah" class="text-center pt-5 mt-2">DATA GOLONGAN DARAH</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-golongan-darah" class="mb-4"></div>
                        <table id="table-blood" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
                <h2 id="jeniskelamin" class="text-center pt-5 mt-2">DATA JENIS KELAMIN</h2>
                <hr>
                <div class="row">
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                    <div class="d-sm-block col-sm-12 col-md-10 col-lg-8">
                        <div id="stat-jenis-kelamin" class="mb-4"></div>
                        <table id="table-gender" class="table-bordered table-sm table table-striped">
                            <thead class="table-primary">
                            <tr>
                                <th class="text-center" rowspan="2" style="width: 60px">No</th>
                                <th class="text-center" rowspan="2">Kelompok</th>
                                <th class="text-center" colspan="2">Jumlah</th>
                            </tr>
                            <tr>
                                <th class="text-center">n</th>
                                <th class="text-center">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-sm-none d-md-inline-block col-md-1 col-lg-2"></div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection

@section('script-head')
    <script>
        @include('root.token')
        const _districts = _data.district, _citizens = _data.citizen, _religions = _data.religion, _educations = _data.education, _occupations = _data.occupation;
        _districts.refresh(function () {
            _response.get('{{url('/districts'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(hamlets,neighboors,families,citizens)')}}', false);
            return _response.response;
        });
        _religions.refresh(function () {
            _response.get('{{url('/religions'.\App\Http\back\_UI::$FLAG_HIDE)}}', false);
            return _response.response;
        });
        _educations.refresh(function () {
            _response.get('{{url('/educations'.\App\Http\back\_UI::$FLAG_HIDE)}}', false);
            return _response.response;
        });
        _occupations.refresh(function () {
            _response.get('{{url('/occupations'.\App\Http\back\_UI::$FLAG_HIDE)}}', false);
            return _response.response;
        });
        _citizens.refresh(function () {
            _response.get('{{url('/citizens'.\App\Http\back\_UI::$FLAG_HIDE.\App\Http\back\_UI::$FLAG_RELATION.'(citeducation.education,citreligion.religion,citoccupation.occupation)')}}', false);
            return _response.response;
        });
    </script>
@endsection

@section('script-body')
    <script>
        function insertTable(table_id, column) {
            const table = document.getElementById(table_id);
            const tbody = table.children[1];
            const row   = document.createElement('tr');
            for (let i = 0; i < column.length; i++) {
                const col = document.createElement('td');
                col.innerHTML = column[i];
                row.appendChild(col);
            }

            tbody.appendChild(row);
        }
        function outputCluster(table_id, label, value, total) {
            for (let i = 0; i < label.length; i++) {
                insertTable(table_id, ['<div class="text-center">' + len + '</div>', label[i], value[i], percentage(total, value[i]) + '%']);
            }
            insertTable(table_id, ['', '<span class="text-muted">Jumlah Total</span>', total + '', '100%']);
        }
        function clusterAge(citizen, sum) {
            const age = _date.age(citizen.year_birth, citizen.month_birth, citizen.dat_birth);
            if (age < 5)
                sum[0] += 1;
            else if (age >= 5 && age <= 15)
                sum[1] += 1;
            else if (age >= 16 && age <= 25)
                sum[2] += 1;
            else if (age >= 26 && age <= 35)
                sum[3] += 1;
            else if (age >= 36 && age <= 45)
                sum[4] += 1;
            else if (age >= 46 && age <= 55)
                sum[5] += 1;
            else if (age >= 56 && age <= 65)
                sum[6] += 1;
            else
                sum[7] += 1;
        }
        function clusterRelation(citizen, relation, sum) {
            const rel = 'cit' + relation;
            sum[citizen[rel][relation].name] += 1;
        }
        function clusterBlood(citizen, sum) {
            if (citizen.blood === 'a+')
                sum[0] += 1;
            else if (citizen.blood === 'a-')
                sum[1] += 1;
            else if (citizen.blood === 'b+')
                sum[2] += 1;
            else if (citizen.blood === 'b-')
                sum[3] += 1;
            else if (citizen.blood === 'ab+')
                sum[4] += 1;
            else if (citizen.blood === 'ab-')
                sum[5] += 1;
            else if (citizen.blood === 'o+')
                sum[6] += 1;
            else if (citizen.blood === 'o-')
                sum[7] += 1;
        }
        function clusterGender(citizen, sum) {
            if (citizen.gender === 'laki-laki')
                sum[0] += 1;
            else
                sum[1] += 1;
        }
        function setRelation(relation) {
            const result_arr = [];
            const result_obj = {};
            let node = relation._first;
            while(node !== undefined) {
                const pointer = node;
                result_arr.push(pointer.name);
                result_obj[pointer.name] = 0;
                node = node._next;
            }

            return [result_arr, result_obj];
        }
        function outputRelation(labels, keys, out) {
            for (let i = 0; i < labels.length; i++) {
                const label = labels[i];
                out.push(keys[label]);
            }
        }

        function percentage(total, val) {
            return ((val / total) * 100).toFixed(2);
        }

        let len       = 1;
        let node_iter = _districts._first;
        const district_label = [];
        const district_sum   = ['', '<span class="text-muted">Jumlah Total</span>', 0, 0, 0, 0,];
        const district_chart = [];
        while (node_iter !== undefined) {
            const district  = node_iter;
            const name      = district.name;
            const hamlets   = district.hamlets.length;
            const neighbors = district.neighboors.length;
            const families  = district.families.length;
            const citizens  = district.citizens.length;
            district_label.push(name);
            district_chart.push(citizens);
            insertTable('table-district', ['<div class="text-center">' + len + '</div>', 'Dusun ' + name, hamlets, neighbors, families, citizens + ' Jiwa']);
            district_sum[2] = district_sum[2] + hamlets;
            district_sum[3] = district_sum[3] + neighbors;
            district_sum[4] = district_sum[4] + families;
            district_sum[5] = district_sum[5] + citizens;
            node_iter = node_iter._next;
            len++;
        }
        district_sum[5] = district_sum[5] + ' Jiwa';
        insertTable('table-district', district_sum);
        document.getElementById('stat-distrik').appendChild(_piechart.init({
            id : 'chart-district',
            height : 120,
            title : 'Perbandingan Data Penduduk Dusun',
            labels : district_label,
        }));
        _piechart.render({
            id : 'chart-district',
            data : district_chart
        });

        const data_religion    = setRelation(_religions);
        const data_education   = setRelation(_educations);
        const data_occupation  = setRelation(_occupations);
        const religion_label   = data_religion[0];
        const religion_keys    = data_religion[1];
        const religion_value   = [];
        const education_label  = data_education[0];
        const education_keys   = data_education[1];
        const education_value  = [];
        const occupation_label = data_occupation[0];
        const occupation_keys  = data_occupation[1];
        const occupation_value = [];
        const age_label        = ['di bawah 6 tahun' ,'6 - 15 tahun' ,'16 - 25 tahun' ,'26 - 35 tahun' ,'36 - 45 tahun' ,'46 - 55 tahun' ,'56 - 65 tahun' ,'di atas 65 tahun'];
        const age_value        = [0 ,0 ,0 ,0 ,0 ,0 ,0 ,0];
        const gender_label     = ['laki-laki','perempuan'];
        const gender_value     = [0, 0];
        const blood_label      = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
        const blood_value      = [0, 0, 0, 0, 0, 0, 0, 0];

        node_iter = _citizens._first;
        console.log(node_iter);
        while (node_iter !== undefined) {
            const citizen  = node_iter;
            clusterRelation(citizen, 'occupation', occupation_keys);
            clusterRelation(citizen, 'education', education_keys);
            clusterRelation(citizen, 'religion', religion_keys);
            clusterGender(citizen, gender_value);
            clusterBlood(citizen, blood_value);
            clusterAge(citizen, age_value);
            node_iter      = node_iter._next;
        }
        outputRelation(religion_label, religion_keys, religion_value);
        outputRelation(education_label, education_keys, education_value);
        outputRelation(occupation_label, occupation_keys, occupation_value);
        outputCluster('table-religion', religion_label, religion_value, _citizens._len);
        outputCluster('table-education', education_label, education_value, _citizens._len);
        outputCluster('table-occupation', occupation_label, occupation_value, _citizens._len);
        outputCluster('table-age', age_label, age_value, _citizens._len);
        outputCluster('table-gender', gender_label, gender_value, _citizens._len);
        outputCluster('table-blood', blood_label, blood_value, _citizens._len);

        document.getElementById('stat-umur').appendChild(_piechart.init({
            id : 'chart-umur',
            height : 120,
            title : 'Perbandingan Data Umur',
            labels : age_label,
        }));
        _piechart.render({
            id : 'chart-umur',
            data : age_value
        });
        document.getElementById('stat-pekerjaan').appendChild(_piechart.init({
            id : 'chart-pekerjaan',
            height : 120,
            title : 'Perbandingan Data Pekerjaan',
            labels : occupation_label,
        }));
        _piechart.render({
            id : 'chart-pekerjaan',
            data : occupation_value
        });
        document.getElementById('stat-pendidikan').appendChild(_piechart.init({
            id : 'chart-pendidikan',
            height : 120,
            title : 'Perbandingan Data Pendidikan',
            labels : education_label,
        }));
        _piechart.render({
            id : 'chart-pendidikan',
            data : education_value
        });
        document.getElementById('stat-agama').appendChild(_piechart.init({
            id : 'chart-agama',
            height : 120,
            title : 'Perbandingan Data Agama',
            labels : religion_label,
        }));
        _piechart.render({
            id : 'chart-agama',
            data : religion_value
        });
        document.getElementById('stat-jenis-kelamin').appendChild(_piechart.init({
            id : 'chart-jenis-kelamin',
            height : 120,
            title : 'Perbandingan Data Jenis Kelamin',
            labels : gender_label,
        }));
        _piechart.render({
            id : 'chart-jenis-kelamin',
            data : gender_value
        });
        document.getElementById('stat-golongan-darah').appendChild(_piechart.init({
            id : 'chart-golongan-darah',
            height : 120,
            title : 'Perbandingan Data Golongan Darah',
            labels : blood_label,
        }));
        _piechart.render({
            id : 'chart-golongan-darah',
            data : blood_value
        });
    </script>
@endsection
