<?php

namespace App\DataTables;

use App\Models\Rating;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class RatingDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'ratings.datatables_actions')->addColumn('nama', function ($data) {
            return $data->products->nama;
        })->addColumn('rating', function ($model) {
            if ($model->rating == '1') {
                return "1 (Sangat Buruk)" ;
            } else if ($model->rating == '2') {
                return "2 (Buruk)" ;
            } else if ($model->rating == '3') {
                return "3 (Cukup)" ;
            } else if ($model->rating == '4') {
                return "4 (Baik)" ; 
            } else if ($model->rating == '5') {
                return "5 (Sangat Baik)" ;
            } else {
                return "Produk ini belum diberi rating" ;
            }
        });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Rating $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Rating $model)
    {
        return $model->newQuery()->with('products')->with('sales_transactions');
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
            'no_pemesanan' => ([
                'data'  => 'sales_transactions.no_pemesanan',
                'name'  => 'sales_transactions.no_pemesanan',
                'title' => 'No Pemesanan',
            ]),
            'nama' => ([
                'data'  => 'products.nama',
                'name'  => 'products.nama',
                'title' => 'Produk',
            ]),
            'rating',
            'ulasan'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ratings_datatable_' . time();
    }
}
