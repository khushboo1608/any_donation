<?php

namespace App\Exports;

use App\Models\User;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

// class UsersExport implements FromCollection, WithHeadings, WithEvents
// {
class UserDataExport implements FromCollection, WithHeadings, WithEvents,WithTitle,WithStrictNullComparison
{
    public function collection()
    {       
        // return ParentCategory::all();
        return User::select('id','login_type','name','email','phone','password','is_verified','otp','address','lat','long','age','gender','state_id','city_id','imageurl','profession','blood_group','is_interested','is_disable')->where('login_type','!=',1)->get();
    }

    public function headings(): array
    {
        return [
            'User Id',
            'User Login Type',
            'User Name',
            'User Email',
            'User Phone No.',
            'Is Verified',
            'Otp',
            'Address',
            'Latitude ',
            'Longitude',
            'Age',
            'Gender',
            'State Id',
            'City Id',
            'Profession',
            'User Image',
            'Blood Group',
            'Is Interested',
            'User Status'
        ];
    }

    public function title(): string
    {
        return 'UsersData';
    }
    
    public function registerEvents(): array
    {
        return [
            // handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
                foreach (range('A','Z') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                 }
                 $event->sheet->getDelegate()->getStyle('A1:S1')
                ->getFont()
                ->setBold(true);
            },
        ];
    }
   

}

