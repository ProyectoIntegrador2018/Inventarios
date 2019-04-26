<?php

namespace App\Exports;

use App\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class LoansExport implements FromQuery, WithHeadings
{
    use Exportable;
    public function __construct(array $query)
    {
      // If provided, filter loans by date
      $this->filterDate = $this->getFilterDate($query['dates']);
      // If provided, filter loans by solicitant type
      $this->filterSolicitant = $this->getFilterSolicitant($query['solicitants']);
      // If provided, filter loans by status
      $this->filterStatus = $this->getFilterStatus($query['status']);
    }

    public function query()
    {
        $loans = DB::table('loans', 'devices', 'applicants')
                    ->join('loan_device', 'loans.id', '=', 'loan_device.loan_id')
                    ->join('devices', 'loan_device.device_id', '=', 'devices.id')
                    ->join('applicants', 'loans.applicant_id', '=', 'applicants.id')
                    ->select('loans.id', 'loans.start_date', 'loans.end_date', 'loans.loan_date', 'loans.return_date', 'loans.status',
                             'devices.name AS deviceName', 'devices.brand', 'devices.model',
                             'applicants.applicant_id', 'applicants.name as applicantName', 'applicants.email')
                    ->whereRaw("{$this->filterSolicitant} AND ({$this->filterStatus})")
                    ->whereRaw("{$this->filterDate}")
                    ->orderBy('loans.id')
                    ->distinct();
        return $loans;
    }

    public function headings(): array
    {
        return [
            '# Préstamo',
            'Fecha inicio',
            'Fecha fin',
            'Fecha entregado',
            'Fecha devuelto',
            'Estado',
            'Dispositivo',
            'Marca',
            'Modelo',
            '# Matrícula',
            'Solicitante',
            'E-mail'
        ];
    }

    private function getFilterDate(array $data)
    {
      $from = date($data["start"]);
      $to   = date($data["end"]);

      if($data["selectAll"])
      {
        return 'TRUE';
      }
      else
      {
        $query = "loans.start_date >= '{$from}' AND";
        $query = "{$query} loans.start_date <= '{$to}'";
        return $query;
      }
    }

    private function getFilterSolicitant(array $data)
    {

      if (includedAllSolicitants($data))? return 'TRUE': return singleSolicitant($data) ;

      // All solicitant types are required
      // if($data["professor"] && $data["student"])
      // {
      //   return 'TRUE';
      // }
      // else
      // {
      //   // Just filter the solicitants that are professors
      //   if($data["professor"])
      //   {
      //     return 'applicants.applicant_id LIKE \'L%\'';
      //   }
      //
      //   // Just filter the solicitants that are students
      //   if($data["student"])
      //   {
      //     return 'applicants.applicant_id LIKE \'A%\'';
      //   }
      // }
    }

    private function getFilterStatus(array $data)
    {
      $pattern = '/\'+(\s+)/i';
      $substitute = '${0} OR ';
      $query = "";

      if ($data["selectAll"])
      {
        return 'TRUE';
      }
      else
      {
        foreach($data["statuses"] as $status) {
          $query = "{$query} loans.status = '{$status}'";
        }
        return preg_replace($pattern, $substitute, $query);;
      }
    }

    private function includedAllSolicitants(array $data)
    {
      return if($data["professor"] && $data["student"]);
    }

    private function singleSolicitant(array $data)
    {
      // Just filter the solicitants that are professors
      if($data["professor"])
      {
        return 'applicants.applicant_id LIKE \'L%\'';
      }

      // Just filter the solicitants that are students
      if($data["student"])
      {
        return 'applicants.applicant_id LIKE \'A%\'';
      }
    }
}
