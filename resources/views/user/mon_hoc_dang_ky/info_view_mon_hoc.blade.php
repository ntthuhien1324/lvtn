<?php
use App\Models\KetQuaDangKy;
use App\Models\DangKyMonHoc;
use App\Models\MonHoc;
use App\Models\DotDangKy;
use App\Models\ThoiGianHoc;
use App\Models\TimeTable;

$idUser = Auth::user()->id;
$dotDangKy = DotDangKy::where('trang_thai', 1)->orderBy('id', 'DESC')->first();
$idDotDangKy = $dotDangKy->id;
$idLopHocPhan = KetQuaDangKy::where('id_sv', $idUser)->where('id_dot_dang_ky', $idDotDangKy)->pluck('id_hoc_phan_dang_ky');
$thoiGianHoc = ThoiGianHoc::whereIn('id_hoc_phan_dang_ky', $idLopHocPhan)->get()->toArray();

$arrDays = ["Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7", "Chủ nhật"];
$arrTietHoc = DB::table('tiet_hoc')->select('gio_bat_dau', 'gio_ket_thuc')->get();
$arrTietHoc = collect($arrTietHoc)->map(function($x){ return (array) $x; })->toArray();


?>
</div>
<div class="row">
    <div class="col-sm-8">
        <h1 class="text-center indam">Thời Khóa Biểu</h1>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="background-color: transparent; border-top-color: white !important; border-left-color: white; " class="th-object"></th>
                <?php
                foreach ($arrDays as $key => $item) {
                    echo "<th style='text-align: center' class='th-object'>" . $item . "</th>";
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $arrayTable = [];
            foreach ($arrTietHoc as $keyTietHoc => $tietHoc) {
                $start = strtotime($tietHoc["gio_bat_dau"]);
                $end = strtotime($tietHoc["gio_ket_thuc"]);
                foreach ($arrDays as $key => $day) {
                    foreach ($thoiGianHoc as $gioHoc) {
                        $startTime = strtotime($gioHoc['gio_bat_dau']);
                        $endTime = strtotime($gioHoc['gio_ket_thuc']);
                        if ($gioHoc['day'] == ($key + 2) && $start >= $startTime && $end <= $endTime) {
                            $idMonHoc = DangKyMonHoc::where('id', $gioHoc['id_hoc_phan_dang_ky'])->first();
                            if (!empty($idMonHoc)) {
                                $idMonHoc = $idMonHoc->id_mon_hoc;
                                $isExisted = false;
                                if(isset($arrayTable[$key])) {
                                    foreach ($arrayTable[$key] as $pSubKey => $item) {
                                        if (isset($item[$idMonHoc])) {
                                            $arrayTable[$key][$pSubKey][$idMonHoc] = $arrayTable[$key][$pSubKey][$idMonHoc] + 1;
                                            $isExisted = true;
                                        }
                                    }
                                }
                                if (!$isExisted) {
                                    $arrayTable[$key][$keyTietHoc][$idMonHoc] = 1;
                                } else {
                                    $arrayTable[$key][$keyTietHoc] = false;
                                }
                            }
                        } else if (!isset($arrayTable[$key][$keyTietHoc])) $arrayTable[$key][$keyTietHoc] = array();

                    }
                }
            }
            foreach ($arrTietHoc as $keyTietHoc => $item) {
                echo "<tr>";
                echo "<td class='td-object'>Tiết " . ($keyTietHoc + 1) . "</td>";

                foreach ($arrDays as $dayKey => $day) {
                    if(isset($arrayTable[$dayKey][$keyTietHoc])) {
                        if ($arrayTable[$dayKey][$keyTietHoc] && count($arrayTable[$dayKey][$keyTietHoc]) > 0) {
                            $count = 1;
                            $idMonHoc = array_keys($arrayTable[$dayKey][$keyTietHoc])[0];
                            $count = array_values($arrayTable[$dayKey][$keyTietHoc])[0];
                            $nameSubject = MonHoc::where("id", $idMonHoc)->first();
                            echo "<td rowspan='$count' style='background-color:#ecf0f1;border-color:Gray;border-width:1px;border-style:solid;height:22px;width:110px;color:Teal;text-align:center;font-weight:bold;' >$nameSubject->name</td>";
                        } else if(is_array($arrayTable[$dayKey][$keyTietHoc])){// nếu như là array thì render
                            echo "<td rowspan='1' class='td-object'></td>";
                        }
                    } else {
                        echo "<td rowspan='1' style='border-color:Gray;border-width:1px;border-style:solid;height:22px;width:110px;'></td>";
                    }
                }
                echo "<td class='td-object'>Tiết " . ($keyTietHoc + 1) . "</td>";

                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-sm-4">
        <h1 class="text-center note">Ghi Chú</h1>
        <ul class="list-unstyled text-center">
            <?php
            $i=0;
            foreach ($arrTietHoc as $key => $tietHoc ){
            ?>
            <li class="notetime"> <?php echo "Tiết ". ($key + 1) . ": " .$tietHoc['gio_bat_dau']." - ".$tietHoc['gio_ket_thuc'];?></li>
            <?php
            }
            ?>

        </ul>
    </div>
</div>
<style type="text/css">
    th.th-object.th-object, td.td-object:first-child, td.td-object:last-child{
        text-align: center;
        background-color: #00a65a;
        color: #fff;
    }
    th.th-object.th-object,.table-bordered>tbody>tr>td.td-object{
        border: 1px solid #000;
        border-top: 1px solid #000 !important;
        width: 200px;
    }
</style>

