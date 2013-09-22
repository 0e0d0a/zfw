<?php
class SYSstat_Controller {
    private $_time = 0;
    private $_mem = 0;


    public function __construct() {
        $this->_time = microtime(true);
        $this->_mem = memory_get_usage(true);
    }
    
    public function getHtml() {
        //log::prd(Application::accessor('classes', 'Cfg')->get('debug','sys'));
        if (Application::accessor('classes', 'Cfg')->get('debug','sys')
        ||Application::accessor('classes', 'Cfg')->get('debug','profiler')
        ||Application::accessor('classes', 'Cfg')->get('debug','DBstat')
        ||Application::accessor('classes', 'Cfg')->get('debug','DBqueries')) {
            
            $out = '
<div id="phpstat" style="border: 1px solid #999999; background-color: #cccccc; height: 200px; width: 100%">
    <ul>'
        .((Application::accessor('classes', 'Cfg')->get('debug','sys')||Application::accessor('classes', 'Cfg')->get('debug','DBstat'))
            ?'<li><a href="#tabStatMain"><span>Main</span></a></li>'
            :'')
        .((Application::accessor('classes', 'Cfg')->get('debug','profiler'))
            ?'<li><a href="#tabProfiler"><span>Profiler</span></a></li>'
            :'')
        .((Application::accessor('classes', 'Cfg')->get('debug','DBqueries'))
            ?'<li><a href="#tabStatDB"><span>Queries</span></a></li>'
            :'').'
    </ul>';
        if (Application::accessor('classes', 'Cfg')->get('debug','sys')||Application::accessor('classes', 'Cfg')->get('debug','DBstat')) {
            $out .= '
    <div id="tabStatMain" style="background-color: #cccccc;">
        <table width="100%">
            '.(Application::accessor('classes', 'Cfg')->get('debug','sys')?'
            <tr>
                <td>Execution time:</td>
                <td>'.round(microtime(true) - $this->_time, 4).' s.</td>
            </tr>
            <tr>
                <td>Memory used:</td>
                <td>'.(memory_get_usage(true) - $this->_mem).' B.</td>
            </tr>':'')
            .(Application::accessor('classes', 'Cfg')->get('debug','DBstat')?'
            <tr>
                <td>SELECT Queries:</td>
                <td>'.DB::$stat['select'].'</td>
            </tr>
            <tr>
                <td>INSERT Queries:</td>
                <td>'.DB::$stat['insert'].'</td>
            </tr>
            <tr>
                <td>UPDATE Queries:</td>
                <td>'.DB::$stat['update'].'</td>
            </tr>
            <tr>
                <td>DELETE Queries:</td>
                <td>'.DB::$stat['delete'].'</td>
            </tr>':'').'
        </table>
    </div>';
        }
        if (Application::accessor('classes', 'Cfg')->get('debug','profiler')) {
            $out .= '<div id="tabProfiler" style="background-color: #cccccc; overflow: scroll; height: 200px; width: 100%">
        <table width="100%">';
            foreach (Application::getProfiler() as $query)
                $out .= 
                '<tr>
                    <td style="border-bottom: 1px dotted #999999">
                        '.($query['success']?'SUCCESS':'FAIL').' '.$query['type'].': '.$query['path'].'
                        <br />
                        execution time: '.(isset($query['exec'])?round($query['exec'], 4):'--').' s., memory usage: '.$query['memory'].'
                    </td>
                </tr>';
            $out .= '
        </table>
    </div>';
        }
        if (Application::accessor('classes', 'Cfg')->get('debug','DBqueries')) {
        $out .= 
    '<div id="tabStatDB" style="background-color: #cccccc; overflow: scroll; height: 200px; width: 100%">
        <table width="100%">';
        foreach (DB::$log as $query)
            $out .= 
                '<tr>
                    <td style="border-bottom: 1px dotted #999999">
                        '.$query['query'].'
                        <br />
                        preparation time: '.round($query['preparing'], 4).' s. , execution time: '.round($query['exec'], 4).' s., '
                        .(isset($query['insertId'])?('insert_id: '.$query['insertId']):('num_rows: '.(isset($query['numRows'])?$query['numRows']:$query['affected']))).'
                    </td>
                </tr>';
        $out .= '
        </table>
    </div>';
    }
        $out .= '
</div>
<script type="text/javascript">
$("#phpstat").tabs({
    load: function(event, ui) {
        $("a", ui.panel).click(function() {
            $(ui.panel).load(this.href);
            return false;
        });
    }
});
</script>';
        return $out;
        }
    }
    
    public function getArray() {
        
    }
}