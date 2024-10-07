<?php
class Test{
    public $id;
    public $date;
    public $name;
    public $product;
    public $url;

    function __construct($id, $name, $product, $url){
        $this->id = $id;
        $this->date = date("d.m.Y H:i:s");
        $this->name = $name;
        $this->product = $product;
        $this->url = $url;
    }
}

$json_data = file_get_contents('data.json');
$data_list = json_decode($json_data, true); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_res = file_get_contents('php://input');
    $data = json_decode($json_res, true);

    // Extract base64 image data and decode it
    $data_image = explode(',', $data['image'])[1]; 
    $data_image = base64_decode($data_image);

    // Ensure image directory exists
    if (!is_dir('./image/')) {
        mkdir('./image/', 0777, true);
    }

    $file_name = 'id_' . count($data_list) . '.png';
    $file_path = './image/' . $file_name;

    // Create new Test object
    $object = new Test(count($data_list), $data['name'], $data['product'], $file_path);

    // Add new object to the data list
    array_push($data_list, $object);

    // Save the image and updated JSON data
    file_put_contents($file_path, $data_image);
    file_put_contents('data.json', json_encode($data_list));
    file_put_contents('test.txt', $data['image']);
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){  

    $id = file_get_contents('php://input');
    unset($data_list[$id]);

    $data_list = array_values($data_list);

    for ($i = 0; $i < count($data_list); $i++) {
        $data_list[$i]['id'] = $i;
    }

    file_put_contents('data.json', json_encode($data_list));
}

echo $json_data;
?>


