<?php
$con=mysqli_connect('localhost','root','','kohk7173_e-learning')
    or die("connection failed".mysqli_errno());

$request=$_REQUEST;
$col =array(
    0   => 'id',
    1   => 'nis',
    2   => 'nama_lengkap',
    3   => 'alamat',
    6   => 'jenis_kelamin'
);  //create column like table in database

$sql ="SELECT * FROM siswa WHERE th_keluar = '9999' ORDER BY nis DESC";
$query=mysqli_query($con,$sql);

$totalData=mysqli_num_rows($query);

$totalFilter=$totalData;

//Search
$sql ="SELECT * FROM siswa WHERE 1=1";
if(!empty($request['search']['value'])){
    $sql.=" AND (nis Like '".$request['search']['value']."%' ";
    $sql.=" OR nama_lengkap Like '".$request['search']['value']."%' ";
    $sql.=" OR jenis_kelamin Like '".$request['search']['value']."%' ";
    $sql.=" OR alamat Like '".$request['search']['value']."%' )";
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
    $subdata[]=$row[1]; //nis
    $subdata[]=$row[2]; //nama_Lengkap
    $subdata[]=$row[3]; //alamat
    $subdata[]=$row[6]; //jenisk           //create event on click in button edit in cell datatable for display modal dialog           $row[0] is id in table on database
    $subdata[]='<a href="?module=m_siswa&act=view_data&id='.$row[0].'" class="btn-sm btn-info"><i class="fas fa-info">&nbsp;</i>Detail</a> | <a href="?module=m_siswa&act=edit_data&id='.$row[0].'" class="btn-sm btn-warning"><i class="fas fa-edit">&nbsp;</i></a>';
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
