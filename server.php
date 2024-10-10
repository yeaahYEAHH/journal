<?php
class Element{
    public int $id;
    public string $date;
    public string $name;
    public string $product;
    public string $url;
    public string $hash;

    function __construct(int $id, string $name, string $product, string $url){
        $this->id = $id;
        $this->date = date("d.m.Y H:i:s");
        $this->name = $name;
        $this->product = $product;
        $this->url = $url;
    }

    function get_keys() : array{
        return array_keys(get_object_vars($this));
    }
}
$json_data = file_get_contents('data.json');
$data_list = json_decode($json_data, true); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_res = file_get_contents('php://input');
    $data = json_decode($json_res, true);

    $data_image = explode(',', $data['image'])[1]; 
    $data_image = base64_decode($data_image);

    if (!is_dir('./image/')) {
        mkdir('./image/', 0777, true);
    }

    $file_name = uniqid('ebat_id_') . '.png';
    $file_path = './image/' . $file_name;

    $object = new Element(count($data_list), $data['name'], $data['product'], $file_path);

    array_push($data_list, $object);

    file_put_contents($file_path, $data_image);
    file_put_contents('data.json', json_encode($data_list));
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){  

    $id = file_get_contents('php://input');

    unlink($data_list[0]['url']);
    unset($data_list[$id]);

    $data_list = array_values($data_list);

    for ($i = 0; $i < count($data_list); $i++) {
        $data_list[$i]['id'] = $i;
    }

    file_put_contents('data.json', json_encode($data_list));
}

// echo $data_list[0]->url;
var_dump($data_list[0]['url']);
// echo $json_data;
?>


