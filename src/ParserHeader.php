<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2015-12-14
 * Time: 22:03
 */

namespace Analytrix;


class ParserHeader
{

  public function getColumns( $data )
  {
      $header_names = array();
#      print_r($data);

      $columns = array();

#      print_r($data->columnHeaders);
 #     die;

      foreach( $data['columnHeaders'] as $column ) {
          #echo "$column\n";
          $header_names[] = $column['name'];
#          var_dump($column);
      }

  #    var_dump($header_names);

      if (!isset($data['rows']))
          return;
      
      foreach ($data['rows'] as $row) {

 #         var_dump($row);
          $count = 0;
          $newRow = array();
          foreach($row as $entry) {
#                var_dump($entry);

                $newEntry[$header_names[$count]] = $entry;
                $count++;
          }

#          print_r($newEntry);
          $columns[] = $newEntry;
      }

      return $columns;
  }


}