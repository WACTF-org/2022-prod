<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';


//Purchase using card token
try {
    //Generating pcidss order to get payment token see more https://docs.fondy.eu/docs/page/4/
    $TestOrderData = [
        'order_id' => time(),
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1232',
        'currency' => 'USD',
        'required_rectoken' => 'Y',
        'amount' => 1000,
        'client_ip' => '127.2.2.1'
    ];
    //Call method to generate order for getting token required
    $Token = Cloudipsp\Pcidss::start($TestOrderData); //next order will be captured
    //Capture request always generated by merchant using host-to-host
    // Required params see more https://docs.fondy.eu/docs/page/10/
    if ($Token->isApproved()) {// Checking if prev payment valid(signature)
        $recurringData = [
            'currency' => 'USD',
            'amount' => 1000,
            'rectoken' => $Token->getData()['rectoken']
        ];
        $recurring_order = Cloudipsp\Payment::recurring($recurringData);
    }
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Purchase using card token</title>
        <style>
            table tr td, table tr th {
                padding: 10px;
            }
        </style>
    </head>
    <body>
    <table style="margin: auto" border="1">
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Request using card token</th>
        </tr>
        <tr>
            <th style="text-align: left"
                colspan="2"><?php printf("<pre>%s</pre>", json_encode(['request' => $recurringData], JSON_PRETTY_PRINT)) ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Response status:</td>
            <td><?= $recurring_order->getData()['response_status'] ?></td>
        </tr>
        <tr>
            <td>Normal response:</td>
            <td>
                <pre><?php print_r($recurring_order->getData()); ?></pre>
            </td>
        </tr>
        <tr>
            <td>Check order is approved:</td>
            <td><?php var_dump($recurring_order->isApproved()); ?></td>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
    <?php
} catch (\Exception $e) {
    echo "Fail: " . $e->getMessage();
}