<?php

namespace App\Traits;

trait RowsTrait
{
    static $pPage = 7;

    static function getMore(&$items, $params = []) {
        $needMore = self::$pPage - count($items);
        $skip = $needMore;

        if ( $needMore > 0 ) {
            $addItems = self::algo($params)->take($needMore)->get();            
            foreach ($addItems as $t) {
                $items->push($t);
            }
            if ( self::$pPage - count($items) > 0 ) {
                $skip = self::getMore($items, $params);
            }
            
        }

        return $skip;
    }

    static function getRows(&$skip, &$page, $params = [])
    {
        $perPage = self::$pPage;
        $items = self::algo($params)->skip($skip + ($page * $perPage))->take($perPage)->get(); 
             
        if ( count($items) > 0 || $page !== 0 && $skip !== 0 ) {
            $needMore = self::getMore($items, $params);
            if ( $needMore > 0 ) {
                $skip = $needMore;
                $page = 0;
            } else {
                $page++;
            }
        }

        $rows = [];        
        $row = [];
        foreach ($items as $key => $t) {            
            $tKey = $key + 1;
            if ( $tKey  < 4 ) {
                $row[] = ['t' => $t];
                $fullRow = $tKey === 3 || $tKey === count($items);
            } else if ( $tKey < 6 ) {
                $row_item = ['t' => $t];
                if ( $tKey === 4 ) {
                    $row_item['2col'] = true;                    
                }
                $row[] = $row_item;
                $fullRow = $tKey === 5 || $tKey === count($items);
            } else if ( $tKey < 8 ) {
                $row_item = ['t' => $t];
                if ( $tKey === 7 ) {
                    $row_item['2col'] = true;                    
                }
                $row[] = $row_item;
                $fullRow = $tKey === 7 || $tKey === count($items);
            }

            if ( $fullRow ) {
                $rows[] = $row;
                $row = [];
            }
        }

        return $rows;
    }
}
