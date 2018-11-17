<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DotDangKy extends Model
{
    use SoftDeletes;

    protected $table = 'dot_dang_ky';

    public function setNamNhapHocAttribute($namNhapHoc)
    {
        if(is_array($namNhapHoc)) {
            $this->attributes['nam_nhap_hoc'] = json_encode($namNhapHoc);
        }
    }

    public function getNamNhapHocAttribute($namNhapHoc)
    {
        return json_decode($namNhapHoc, true);
    }

    public function setTrangThaiImportDiemAttribute($trangThaiImportDiem)
    {
        if(is_array($trangThaiImportDiem)) {
            $this->attributes['trang_thai_import_diem'] = json_encode($trangThaiImportDiem);
        }
    }

    public function getTrangThaiImportDiemAttribute($trangThaiImportDiem)
    {
        return json_decode($trangThaiImportDiem, true);
    }

    public function setTrangThaiSuaDiemAttribute($trangThaiSuaDiem)
    {
        if(is_array($trangThaiSuaDiem)) {
            $this->attributes['trang_thai_sua_diem'] = json_encode($trangThaiSuaDiem);
        }
    }

    public function getTrangThaiSuaDiemAttribute($trangThaiSuaDiem)
    {
        return json_decode($trangThaiSuaDiem, true);
    }
}
