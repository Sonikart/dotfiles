<?php

$title = "Monitoring v1"; 
$servers = array(
    'Teufeurs.eu' => array(
        'ip' => '127.0.0.1',
        'port' => 80,
        'info' => 'Information',
        'purpose' => 'No Purpose'
    ),
    'La conciergerie du geek' => array(
        'ip' => '127.0.0.1',
        'port' => 22,
        'info' => 'Informations',
        'purpose' => 'No purpose'
    ),
    'Bot discord' => array(
        'ip' => '127.0.0.1',
        'port' => 8097,
        'info' => 'Informations',
        'purpose' => 'No purpose'
    ),
    
);

    if (isset($_GET['host'])) 
    {
        $host = $_GET['host'];

        if (isset($servers[$host])) 
        {
            header('Content-Type: application/json');

            $return = array(
                'status' => test($servers[$host])
            );

            echo json_encode($return);
            exit;
        } 
        else 
        {
            header("404 Not Found");
        }
    }

    $names = array();

    foreach ($servers as $name => $info) 
    {
        $names[$name] = md5($name);
    }
    function test($server) 
    {
        $socket = @fsockopen($server['ip'], $server['port'], $errorNo, $errorStr, 3);

        if ($errorNo == 0) 
        {
            return true;
        } 
        else 
        {
        return false;
        }
    }

    function in_array_r($needle, $haystack, $strict = false) 
    {
        foreach ($haystack as $item) 
        {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) 
            {
                return true;
            }
        }
        return false;
    }
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/2.3.2/cosmo/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css">
    </head>
    <body>

        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nom du site</th>
                        <th>Adresse ip</th>
                        <th>Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($servers as $name => $server): ?>
                        <tr id="<?php echo md5($name); ?>">
                            <td><i class="icon-spinner icon-spin icon-large"></i></td>
                            <td class="name"><?php echo $name; ?></td>
                            <td><?php echo $server['info']; ?></td>
                            <td><?php echo $server['purpose']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script type="text/javascript">
            function test(host, hash) {
                var request;
                request = $.ajax({
                    url: "<?php echo basename(__FILE__); ?>",
                    type: "get",
                    data: {
                        host: host
                    },
                    beforeSend: function () {
                        $('#' + hash).children().children().css({'visibility': 'visible'});
                    }
                });
                request.done(function (response, textStatus, jqXHR) {
                    var status = response.status;
                    var statusClass;
                    if (status) {
                        statusClass = 'success';
                    } else {
                        statusClass = 'error';
                    }
                    $('#' + hash).removeClass('success error').addClass(statusClass);
                });
                request.fail(function (jqXHR, textStatus, errorThrown) {
                    console.error(
                        "The following error occured: " +
                            textStatus, errorThrown
                    );
                });
                request.always(function () {
                    $('#' + hash).children().children().css({'visibility': 'hidden'});
                })
            }
            $(document).ready(function () {
                var servers = <?php echo json_encode($names); ?>;
                var server, hash;
                for (var key in servers) {
                    server = key;
                    hash = servers[key];
                    test(server, hash);
                    (function loop(server, hash) {
                        setTimeout(function () {
                            test(server, hash);
                            loop(server, hash);
                        }, 60000);
                    })(server, hash);
                }
            });
        </script>
    </body>
</html>
<?php

?>