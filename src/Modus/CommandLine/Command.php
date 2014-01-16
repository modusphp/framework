<?php

namespace Modus\CommandLine;

class Command {
    
    protected $_procs = array();
    
    protected function _execute($command, &$pipes = array()) {
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w'),
        );

        $handle = proc_open("$command", $descriptorspec, $pipes);
        return $handle;
    }
    
    public function blockingCommand($command) {
        $pipes = array(0 => null, 1 => null, 2 => null);
        $handle = $this->_execute($command, $pipes);
        $running = true;

        while($running) {
            $status = proc_get_status($handle);
            $running = $status['running'];
        }
                
        $results = array();

        $results['message'] = stream_get_contents($pipes[1]);
        $results['error'] = stream_get_contents($pipes[2]);

        return $results;
    }
    
    public function nonBlockingCommand($command) {
        $pipes = array(0 => null, 1 => null, 2 => null);
        
        $handle = $this->_execute($command, $pipes);
        $this->_procs[] = array('handle' => $handle, 'pipes' => $pipes);
    }
    
    public function getProcs() {
        return $this->_procs;
    }
}





