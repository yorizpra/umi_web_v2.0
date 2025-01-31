<?php

namespace App\DataTables;

use App\Models\EventRegister;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class EventRegisterDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'event_registers.datatables_actions')->addColumn('jenis_kelamin', function ($data) {
            if ($data->jenis_kelamin == 'L') {
                return "Laki-laki" ;
            } else {
                return  "Perempuan";
            }
        })->addColumn('foto', function($data){
            return '<img src="'.$data->foto.'" width="50px" style="border-radius: 25%">';
        })
        ->rawColumns(['foto', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EventRegister $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EventRegister $model)
    {
        return $model->newQuery()->with('events')->with('users');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => false,
                'order'     => [[0, 'desc']],
                'buttons'   => [
                    ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
                'initComplete' => "function () {
                    var kolom = this.api().columns();
                    kolom.every(function (i) {

                        if(i === kolom['0'].length - 1){
                            return false;
                        }
                        var column = this;
                        var input = document.createElement(\"input\");
                        input.setAttribute('id', i);
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val()).draw();
                        }).attr('placeholder', 'Search');                        
                    }); 
                }",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'event_id' => ([
                'data' => 'events.title',
                'name' => 'events.title',
                'title' => 'Judul Acara'
            ]),
            'user_id' => ([
                'data' => 'users.name',
                'name' => 'users.name',
                'title' => 'Nama Penyelenggara'
            ]),
            'name' => ['title' => 'Nama Pendaftar'],
            'jenis_kelamin',
            'tanggal_lahir',
            'no_hp',
            'foto',
            'city' => ['title' => 'Asal Kota'],
            'subdistrict' => ['title' => 'Asal Desa'],
            'full_address' => ['title' => 'Alamat Lengkap'],
            'created_at' => [
                'title' => 'Tanggal Daftar',
                'visible' => false
            ],
            'updated_at' => [
                'title' => 'Tanggal Update',
                'visible' => false
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'event_registers_datatable_' . time();
    }
}
