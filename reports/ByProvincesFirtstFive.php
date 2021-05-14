<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <title>Relatório Provincial</title>
    <style>
        *{
            box-sizing: border-box;
        }
        #container-table{
            width:100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            align-content: space-between;
            
        }
        
        img{
            width: 150px;
        }
        .image-div{
            width: 100%;
            text-align: center;
        }
        h2,h3,h4{
            text-align: center;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        table,td,th{
            text-align: center;
            border-collapse: collapse;
            border: 0.5px solid white;
        }
        table{
            width: 100%;
            color:white;
           
        }
        #table-data{
            margin-top:20%;
            width:100%;

        }
        #table-data thead{
            background-color: #00aeef;
            font-weight: 300;
            font-family: 'Montserrat', sans-serif;
            text-align:center;
            font-size:15px;


        }

        #table-data tbody{
            background-color: #0072bc;
            font-family: 'Montserrat', sans-serif;
            font-size:13px;
        }
        #table-data-impress{
            color:#0072bc;
            text-align:justify !important;
            text-indent:5px;
            background:rgba(0,0,0,.1);
            width:100%;
        }
        #table-data-impress td{
            border:0.5px solid black;
        }

       

    </style>
   </head>
<body>
    <div class="image-div">
    <img src='http://localhost/srad/uploads/transferir.png' />
    </div>
<h2>República de Angola</h2>
    <h3>Ministério da Saúde</h3>
    <h4>Direção Nacional de Saúde</h4>

    <div id="container-table">
    <div>
    <table id="table-data-impress">
    <tbody>
           <tr>
                <td>
                    Gerado Por : Franco Mufinda
                </td>
                <td>
                    Data :  <?= date("d/m/Y") ?>
                </td>
               
           </tr>
        </tbody>
    </table>
    </div>
    <div>
    <table id="table-data">
        <thead>
        <tr>
        <th rowspan="3">
                    Provincias
                </th>
                <td colspan="<?= $counter * 3?>">Doenças</td>
            </tr>
            <tr>
            <?php foreach($diseaseFor as $disease):?>
                <th colspan="3">
                    <?= $disease["doenca"] ?>  
                </th>
            <?php endforeach; ?>
            </tr>
            
            <tr>
            <?php for($i = 0;$i<$counter;$i++): ?>
                <th>
                    M
                </th>
                <th>
                    R
                </th>
                <th>
                   A
                </th>
            <?php endfor; ?>

            </tr>
        </thead>
        <tbody>     
        <?php 
            foreach($provinces as $province):
        ?>
        
        <tr>
        
                <td>
                    <?= $province->nome ?>
                </td>
                <?php 
            foreach($diseases as $disease):
                
                $totalDeath += isset($json[$province->nome][0][$disease->nome]) ? $json[$province->nome][0][$disease->nome]["morte"] : 0;
                $totalActives += isset($json[$province->nome][0][$disease->nome]) ? $json[$province->nome][0][$disease->nome]["activo"] : 0;
                $totalRecovereds += isset($json[$province->nome][0][$disease->nome]) ?  $json[$province->nome][0][$disease->nome]["recuperado"] : 0
        ?>  
                <td>
                <?= isset($json[$province->nome][0][$disease->nome]) ? $json[$province->nome][0][$disease->nome]["morte"] : 0 ?>
                </td>
                <td>
                <?= isset($json[$province->nome][0][$disease->nome]) ? $json[$province->nome][0][$disease->nome]["recuperado"] : 0 ?>
                </td>
                <td>
                <?= isset($json[$province->nome][0][$disease->nome]) ?  $json[$province->nome][0][$disease->nome]["activo"] : 0 ?>
                </td>      
            <?php endforeach; ?>      
            </tr>
        <?php endforeach; ?>
        <tr>
        <td>Total</td>
        <td></td>
        <td></td>
        <td></td>

        </tr>
        </tbody>
    </table>
    </div>
    <!--<img src="?= $res['url'] ?">-->
</div>
</body>
</html>