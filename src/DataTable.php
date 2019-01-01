<?php
namespace Datatable;
/**
 * 
 */
class DataTable
{

	
	public function make($model){
		$request=$_REQUEST;
		$mdl=$model['model'];
		if(!isset($request['draw'])){
			return ['data'=>$mdl->get()->toArray()];
		}
		$columns=$request['columns'];
		$columnsNameSelect="";
		$columnsNameSelectArray=[];
		$columnsNameSelectArray2=[];
		foreach ($columns as $key => $value) {
			$data=$value['data'];
			$data=preg_replace('/[0-9]+/','', $data);
			$columnsNameSelect=$columnsNameSelect.$data.",";
			if($data){
				array_push($columnsNameSelectArray, $data);
				array_push($columnsNameSelectArray2, [$key=>$data]);
			}
		}
		$columnsNameSelect=rtrim($columnsNameSelect,",");
		if($columnsNameSelect[0]==','){
			$columnsNameSelect=ltrim($columnsNameSelect,", ");
		}
		$totalData=$mdl->count();
		$totalFiltered=$totalData;
		if( !empty($request['search']['value']) ) {
			$sql="";
			foreach ($columnsNameSelectArray as $key => $value) {
				$and='';
				if($key==0){
					$and='';
				}else{
					$and='OR';
				}
				$columns_select=$mdl->columns;
				$col=$value;
				if($columns_select){
					foreach ($columns_select as $key_select => $value_select) {

						if(preg_match("/(as)/", $value_select)){
							$split_alias=explode("as", $value_select);
							$col=$split_alias[0];
						}
					}
				}
				$sql.=" $and $col LIKE '".$request['search']['value']."%' "; 
			}
			$mdl->whereRaw($sql);
		}
		$totalFiltered=$mdl->count();
		$numCol=$request['order'][0]['column'];
		if($numCol>1){
			$numCol=$numCol-1;
		}
		$order=$columnsNameSelectArray[$numCol];
		$type_order=$request['order'][0]['dir'];
		if($type_order=='asc'){
			$type_order='ASC';
		}elseif($type_order=='desc'){
			$type_order='DESC';
		}
		$mdl->orderBy($order,$type_order);
		$mdl->limit($request['length']);
		$mdl->offset($request['start']);
		$datas["draw"]=intval( $request['draw'] );
		$datas["recordsTotal"]=intval( $totalData );
		$datas["recordsFiltered"]=intval( $totalFiltered );
		$datas["data"]=$mdl->get()->toArray();
		return response()->json($datas);
	}

}
