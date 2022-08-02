<?php

namespace App\Parsers;

class ScheduleParser
{
    private $date;
    private $group;

    public function __construct($date, $group)
    {
        $this->date = $date;
        $this->group = $group;
    }

    public function parse(){
        $nodes = $this->getNodes();
        $result = [];
        foreach ($nodes as $node){
            if ($node->childNodes->length > 1){
                $r = [];
                foreach ($this->getTableFromNode($node) as $childNode){
                    try {
                        $childNode = $childNode->childNodes->item(0);
                        $r[] = $this->formatingSchedule($childNode);
                    }catch (\ErrorException $e){

                    }
                }
                $result[] = $r;
            }
        }
        return $result;
    }

    private function requestForSchedule(){//TODO remade request on guzzle
        $url = "http://asu.pnu.edu.ua/cgi-bin/timetable.cgi?n=700";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $headers = array("Content-Type: application/x-www-form-urlencoded");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = "faculty=1002&teacher=''&course=2&group=".
            iconv('UTF-8', 'windows-1251', $this->group).
            "&sdate={$this->date}&edate={$this->date}&n=700";
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        logs()->info(iconv('UTF-8', 'windows-1251', utf8_decode($this->group)));
        return $resp;
    }

    private function str_replace_first($search, $replace, $subject)
    {
        $search = '/'.preg_quote($search, '/').'/';
        return preg_replace($search, $replace, $subject, 1);
    }

    private function getHtml(){
        $html = $this->requestForSchedule();
        $html = str_replace('id="sdate"', '', $html);
        $html = str_replace('id="edate"', '', $html);
        $html = str_replace('href="./timetable.cgi?n=700&group=6742"', '', $html);
        return str_replace('<table class="table  table-bordered table-striped">',
            '<table class="table  table-bordered table-striped"><div class="row">', $html);
    }

    private function getNodes(){
        $dom = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML(
            $this->getHtml()
        );
        libxml_use_internal_errors($internalErrors);
        $finder = new \DOMXPath($dom);
        $classname="col-md-6";
        return $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    }

    private function formatingSchedule($childNode){
        $subject = false;
        if ($childNode->childNodes->item(2)->childNodes->item(4) != null){
            $subject = $childNode->childNodes->item(2)->childNodes->item(4)->data;
        }
        return [
            "start" => $childNode->childNodes->item(1)->childNodes->item(0)->data,
            "end" => $childNode->childNodes->item(1)->childNodes->item(2)->data,
            "room" => $childNode->childNodes->item(2)->childNodes->item(0)->data,
            "name" => !$subject ? trim($childNode->childNodes->item(2)->childNodes->item(2)->data):
                trim($childNode->childNodes->item(2)->childNodes->item(2)->data) . $subject
        ];
    }

    private function getTableFromNode($node){
        return $node->getElementsByTagName('table')->item(0)->childNodes;
    }

    private function getDateFromNode($node){
        return $node->getElementsByTagName('h4')->item(0)->childNodes->item(0)->data;
    }
}
