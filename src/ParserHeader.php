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

  /**
   * Return all column headers 
   * 
   * @param  array $data Data array returned from Google Analytics
   * @return array       List of column labels
   */
  public function getColumnNames( $data ) 
  {
      foreach( $data['columnHeaders'] as $column ) {
          $header_names[] = $column['name'];
      }
      return $header_names;
  }

 /**
  * Return all column headers with the date label removed 
  * 
  * @param  array $data Data array returned from Google Analytics
  * @return array       List of column labels
  */
 public function getColumnNamesWithoutDate( $data ) 
 {
    $headers = $this->getColumnNames( $data );
    $reduced = array_diff( $headers, array('ga:date') );
    return $reduced;
 } 

 public function getColumns( $data )
  {
      $header_names = array();
      $columns = array();

      foreach( $data['columnHeaders'] as $column ) {
          $header_names[] = $column['name'];
      }

      if (!isset($data['rows']))
          return;

      foreach ($data['rows'] as $row) {

          $count = 0;
          $newRow = array();
          foreach($row as $entry) {
                $newEntry[$header_names[$count]] = $entry;
                $count++;
          }

          $columns[] = $newEntry;
      }

      return $columns;
  }

   public function getColumnAsArray( $columnName, $data )
   {
       $parsed = $this->getColumns($data);

       $found = array();

       foreach( $parsed as $item ) {
           if (isset($item[$columnName]))
           {
               $found[] = $item[$columnName];
           }
       }

       return $found;

   }

}