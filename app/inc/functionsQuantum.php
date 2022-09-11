<?php
class consumeQuantum
{
    private $headers;
    private $QuantumRestUrl = 'wsv3';
    public function __construct()
    {
        $this->headers = [
            "user:chef",
            "token:fKqm02uN",
            "Content-Type:application/json"
        ];
    }

    public function getBrands()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://" . $this->QuantumRestUrl . ".activarpromo.com/api/getbrands.json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        $server_output = curl_exec($ch);
        $output = json_decode($server_output, true);
        curl_close($ch);
        return $output["response"]["message"];
    }

    public function getProductsByBrand($brand)
    {
        $postData = ['brand_id' => $brand];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://" . $this->QuantumRestUrl . ".activarpromo.com/api/getproducts.json");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $server_output = curl_exec($ch);
        $output = json_decode($server_output, true);
        curl_close($ch);
        return $output["response"]["message"];
    }
}
