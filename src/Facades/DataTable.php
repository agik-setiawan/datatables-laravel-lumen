<?php
namespace Datatable\Facades;
use Illuminate\Support\Facades\Facade;
/**
 * 
 */
class DataTable extends Facade
{
	protected static function getFacadeAccessor() { return 'datatable'; }
}
?>