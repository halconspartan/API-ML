
<html>

<head></head>

<body>
  <?php
    $strJsonFileContents = file_get_contents("C:/Users/yorch/Desktop/mlm.json");

    $array = json_decode($strJsonFileContents, true);

    $id = array();
    $path = array();

    foreach ($array as $k=>$v){
      foreach ($v as $kk=>$vv){
        if($kk == "path_from_root" AND sizeof($v["path_from_root"])==1){
          array_push($id, $k);
        }   
        if ($kk == "path_from_root") { 
           array_push($path,$vv);    
        }
      }
      //echo "<hr />";
    }
    $ruta ="C:/Users/yorch/Desktop/categorias.csv";
    $ruta2 ="C:/Users/yorch/Desktop/productos.csv";
    $productos = array();
    generarCSV($path, $ruta, $delimitador = ';', $encapsulador = '"');

    function generarCSV($arreglo, $ruta, $delimitador, $encapsulador){
      $file_handle = fopen($ruta, 'w');
      foreach ($arreglo as $linea) {
        foreach($linea as $data){
          fputcsv($file_handle, $data, $delimitador, $encapsulador);
        }
        fwrite($file_handle,"\n");
      }

      rewind($file_handle);
      fclose($file_handle);
    }

    function generarCSV2($arreglo, $ruta2, $delimitador, $encapsulador){
      $file_handle = fopen($ruta2, 'w');
      foreach ($arreglo as $linea) {
          fputcsv($file_handle, $linea, $delimitador, $encapsulador);
          fwrite($file_handle,"\n");
      }

      rewind($file_handle);
      fclose($file_handle);
    }

    //foreach($id as $idc){
      $ch = curl_init();
      $offset = 0;
      $limit = 50;
      //$USER_ID = 541245689;
      $ACCESS_TOKEN = 'APP_USR-2200485265577153-102323-0c67431f7e7550ecd95df68d76a4f4e3-541245689';

      $id1=$id[0];

      //curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/{$USER_ID}/sites/MLM/search?category={$id1}&offset={$offset}&limit={$limit}&access_token={$ACCESS_TOKEN}");
      curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/sites/MLM/search?category={$id1}&access_token={$ACCESS_TOKEN}&offset={$offset}&limit={$limit}");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $res= curl_exec($ch);

      $datos = json_decode($res,true);
      foreach ($datos as $ke=>$va){
          if($ke=="paging"){
            for ($i = 50; $i <= 9999; $i+=50) { //$va["total"]
              //$USER_ID = 541245689;
              $ACCESS_TOKEN = 'APP_USR-2200485265577153-102323-0c67431f7e7550ecd95df68d76a4f4e3-541245689';
              $l = 50;
              curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/sites/MLM/search?category={$id1}&offset={$i}&limit={$l}&access_token={$ACCESS_TOKEN}");
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $res2 = curl_exec($ch);
              $datos2 = json_decode($res2,true);
              foreach ($datos2 as $key=>$val){
                if($key=="results"){
                  foreach ($val as $linea2){
                    $productosar = array();
                    foreach ($linea2 as $keyw=>$valu){
                      switch ($keyw) {
                        case "id":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                        case "title":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                        case "seller":
                            foreach($valu as $keyword=>$values2){
                              if($keyword=="id"){
                                curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/{$values2}");
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $res3 = curl_exec($ch);
                                $datos3 = json_decode($res3,true);
                                foreach ($datos3 as $keyword2=>$values3){
                                  if($keyword2=="nickname"){
                                    array_push($productosar, $keyword2." ".$values3);
                                  }
                                }
                             }
                            }
                            break;
                        case "price":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                        case "sold_quantity":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                        case "address":
                             foreach($valu as $keywo=>$value){
                               if($keywo=="state_name"){
                                  array_push($productosar, $keywo." ".$value);
                               }elseif($keywo=="city_name"){
                                  array_push($productosar, $keywo." ".$value);
                               }
                             } 
                            break;
                        case "attributes":
                             $at0=array_shift($valu);
                             foreach($at0 as $keywor=>$values){
                              if($keywor=="value_name"){
                                array_push($productosar, $values);
                              }
                             }
                            break;
                        case "category_id":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                        case "domain_id":
                            array_push($productosar, $keyw." ".$valu);
                            break;
                      }
                    }
                    array_push($productos,$productosar);
                  }
                }
              }
            }
          } elseif($ke == "results"){
            foreach ($va as $linea2){
              $productosar2 = array();
              foreach ($linea2 as $keyw=>$valu){
                switch ($keyw) {
                  case "id":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                  case "title":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                  case "seller":
                    foreach($valu as $keyword=>$values2){
                      if($keyword=="id"){
                        curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/{$values2}");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $res3 = curl_exec($ch);
                        $datos3 = json_decode($res3,true);
                        foreach ($datos3 as $keyword2=>$values3){
                          if($keyword2=="nickname"){
                            array_push($productosar2, $keyword2." ".$values3);
                          }
                        }
                     }
                    }
                      break;
                  case "price":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                  case "sold_quantity":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                  case "address":
                       foreach($valu as $keywo=>$value){
                         if($keywo=="state_name"){
                            array_push($productosar2, $keywo." ".$value);
                         }elseif($keywo=="city_name"){
                            array_push($productosar2, $keywo." ".$value);
                         }
                       } 
                      break;
                  case "attributes":
                       $at0=array_shift($valu);
                       foreach($at0 as $keywor=>$values){
                        if($keywor=="id"){
                          array_push($productosar2, $values);
                        }elseif($keywor=="value_name"){
                          array_push($productosar2, $values);
                        }
                       }
                      break;
                  case "category_id":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                  case "domain_id":
                      array_push($productosar2, $keyw." ".$valu);
                      break;
                }
              }
                array_push($productos,$productosar2);
            }
          }
      }
      curl_close($ch);
    //}  
    generarCSV2($productos, $ruta2, $delimitador = ';', $encapsulador = '"');
    //print_r(array_keys($array));

  ?>
</body>

</html>

