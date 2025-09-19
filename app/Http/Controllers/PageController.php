<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Dịch vụ pages
    public function dichVuGiaoHang()
    {
        return view('services.giao-hang');
    }

    public function dichVuXeTai()
    {
        return view('services.xe-tai');
    }

    public function dichVuChuyenNha()
    {
        return view('services.chuyen-nha');
    }

    public function dichVuDoanhNghiep()
    {
        return view('services.doanh-nghiep');
    }

    // Khách hàng pages
    public function khachHangCaNhan()
    {
        return view('customers.ca-nhan');
    }

    public function khachHangDoanhNghiep()
    {
        return view('customers.doanh-nghiep');
    }

    public function congDongHoTro()
    {
        return view('customers.cong-dong-ho-tro');
    }

    // Tài xế pages
    public function dangKyTaiXe()
    {
        return view('drivers.dang-ky');
    }

    public function congDongTaiXe()
    {
        return view('drivers.cong-dong');
    }

    public function camNangTaiXe()
    {
        return view('drivers.cam-nang');
    }

    // Hỗ trợ pages
    public function hoTroKhachHang()
    {
        return view('support.khach-hang');
    }

    public function hoTroTaiXe()
    {
        return view('support.tai-xe');
    }

    // Tuyển dụng pages
    public function veChungToi()
    {
        return view('recruitment.ve-chung-toi');
    }

    public function cauChuyenCourierXpress()
    {
        return view('recruitment.cau-chuyen');
    }

    public function giaNhapCourierXpress()
    {
        return view('recruitment.gia-nhap');
    }
}
