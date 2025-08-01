<?php
$con=mysqli_connect('localhost','kokh7173_e-learning','kokh7173_e-learning','kokh7173_e-learning')
    or die("connection failed".mysqli_errno());

$request=$_REQUEST;
$col =array(
    0   =>  'id_user',
    1   =>  'username',
    3   =>  'nama_lengkap',
    4   =>  'level',
    7   =>  'blokir'
);  //create column like table in database

$sql ="SELECT * FROM user";
$query=mysqli_query($con,$sql);

$totalData=mysqli_num_rows($query);

$totalFilter=$totalData;

//Search
$sql ="SELECT * FROM user WHERE 1=1";
if(!empty($request['search']['value'])){
    $sql.=" AND (username Like '".$request['search']['value']."%' ";
    $sql.=" OR nama_lengkap Like '".$request['search']['value']."%' ";
    $sql.=" OR level Like '".$request['search']['value']."%' ";
    $sql.=" OR blokir Like '".$request['search']['value']."%' )";
}
$query=mysqli_query($con,$sql);
$totalData=mysqli_num_rows($query);

//Order
$sql.=" ORDER BY ".$col[$request['order'][0]['column']]."   ".$request['order'][0]['dir']."  LIMIT ".
    $request['start']."  ,".$request['length']."  ";

$query=mysqli_query($con,$sql);

$data=array();

while($row=mysqli_fetch_array($query)){
    $subdata=array();
    $subdata[]=$row[1]; //username
    $subdata[]=$row[3]; //nama_lkp
    $subdata[]=$row[4]; //level
    $subdata[]=$row[7]; //blokir           //create event on click in button edit in cell datatable for display modal dialog           $row[0] is id in table on database
    $subdata[]='<a href="?module=user&act=edit_data&id='.$row[0].'" class="btn-sm btn-warning"><i class="fas fa-edit">&nbsp;</i>Edit</a>';
        //<a href="index.php?delete='.$row[0].'" onclick="return confirm(\'Are You Sure ?\')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash">&nbsp;</i>Delete</a>';
    $data[]=$subdata;
}

$json_data=array(
    "draw"              =>  intval($request['draw']),
    "recordsTotal"      =>  intval($totalData),
    "recordsFiltered"   =>  intval($totalFilter),
    "data"              =>  $data
);

echo json_encode($json_data);

?>
