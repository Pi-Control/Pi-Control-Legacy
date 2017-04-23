<?php

class ProcessController
{
	
	private $rawOutput;
	private $processes;
	
	private function runCommand()
	{
		$this->rawOutput = shell_exec('ps axo pid,ppid,user,stat,pcpu,pmem,etime,time,comm | tail -n +2');
	}
	
	private function handleRawOutput()
	{
		if ($this->rawOutput === NULL)
			$this->runCommand();
		
		$output = explode("\n", $this->rawOutput);
		
		foreach ($output as $process)
		{
			if (empty($process))
				continue;
			
			$split = preg_split('#[\s]+#', $process);
			
			if ($split[0] == '')
			{
				unset($split[0]);
				$split = array_values($split);
			}
			
			$newProcess = new ProcessEntry();
			$newProcess->setPid($split[0]);
			$newProcess->setPpid($split[1]);
			$newProcess->setUser($split[2]);
			$newProcess->setStatus($split[3]);
			$newProcess->setCpu($split[4]);
			$newProcess->setRam($split[5]);
			$newProcess->setElapsedTime($split[6]);
			$newProcess->setRuntime($split[7]);
			$newProcess->setCommand($split[8]);
			
			$this->processes[] = $newProcess;
		}
	}
	
	public function getProcesses()
	{
		if ($this->rawOutput === NULL)
			$this->handleRawOutput();
		
		return $this->processes;
	}
	
	public function getCount()
	{
		if ($this->rawOutput === NULL)
			$this->handleRawOutput();
		
		return count($this->processes);
	}
	
	public function getCountRunning()
	{
		if ($this->rawOutput === NULL)
			$this->handleRawOutput();
		
		$count = 0;
		
		foreach ($this->processes as $process)
		{
			if ($process->getStatus() == 'R')
				$count += 1;
		}
		
		return $count;
	}
	
	static public function isPidWithStartTimeExists($pid, $time) {
		if (!function_exists('getStartTimeFromTime'))
			(include_once LIBRARY_PATH.'process/process.function.php');
		
		$rawOutput = shell_exec('ps -eo pid,etime | grep -E \'^[[:space:]]*'.escapeshellarg($pid).' \' | head -n 1');
		
		$split = preg_split('#[\s]+#', $rawOutput);
		
		if ($split[0] == '')
		{
			unset($split[0]);
			$split = array_values($split);
		}
		
		return ($split[0] == $pid && abs(getStartTimeFromTime($split[1]) -  $time) < 10);
	}
	
	public function terminatePid($pid)
	{
		global $tpl;
		
		list ($SSHReturn, $SSHError, $SSHExitStatus) = $tpl->executeSSH('sudo kill -SIGTERM '.escapeshellarg($pid));
		
		return ($SSHError == '' && $SSHExitStatus == 0) ? true : false;
	}
	
	public function killPid($pid)
	{
		global $tpl;
		
		list ($SSHReturn, $SSHError, $SSHExitStatus) = $tpl->executeSSH('sudo kill -SIGKILL '.escapeshellarg($pid));
		
		return ($SSHError == '' && $SSHExitStatus == 0) ? true : false;
	}
}

class ProcessEntry
{

	private $pid;
	private $ppid;
	private $user;
	private $status;
	private $cpu;
	private $ram;
	private $elapsedTime;
	private $runtime;
	private $command;
	
	public function getPid()
	{
		return $this->pid;
	}
	
	public function setPid($pid)
	{
		$this->pid = $pid;
	}
	
	public function getPpid()
	{
		return $this->ppid;
	}
	
	public function setPpid($ppid)
	{
		$this->ppid = $ppid;
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getCpu()
	{
		return $this->cpu;
	}
	
	public function setCpu($cpu)
	{
		$this->cpu = $cpu;
	}
	
	public function getRam()
	{
		return $this->ram;
	}
	
	public function setRam($ram)
	{
		$this->ram = $ram;
	}
	
	public function getElapsedTime()
	{
		return $this->elapsedTime;
	}
	
	public function setElapsedTime($elapsedTime)
	{
		$this->elapsedTime = $elapsedTime;
	}
	
	public function getRuntime()
	{
		return $this->runtime;
	}
	
	public function setRuntime($runtime)
	{
		$this->runtime = $runtime;
	}
	
	public function getCommand()
	{
		return $this->command;
	}
	
	public function setCommand($command)
	{
		$this->command = $command;
	}
	
	

}

?>