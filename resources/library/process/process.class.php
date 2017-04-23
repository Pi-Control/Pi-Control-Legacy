<?php

class ProcessController
{
	
	private $rawOutput;
	private $processes;
	
	private function runCommand()
	{
		$this->rawOutput = shell_exec('top -b -n 1 | tail -n +8');
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
			$newProcess->setUser($split[1]);
			$newProcess->setPriority($split[2]);
			$newProcess->setNice($split[3]);
			$newProcess->setVirtualMemory($split[4]);
			$newProcess->setPhysicalMemory($split[5]);
			$newProcess->setSharedMemory($split[6]);
			$newProcess->setStatus($split[7]);
			$newProcess->setCpu($split[8]);
			$newProcess->setRam($split[9]);
			$newProcess->setRuntime($split[10]);
			$newProcess->setCommand($split[11]);
			
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
}

class ProcessEntry
{

	private $pid;
	private $user;
	private $priority;
	private $nice;
	private $virtualMemory;
	private $physicalMemory;
	private $sharedMemory;
	private $status;
	private $cpu;
	private $ram;
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
	
	public function getUser()
	{
		return $this->user;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getPriority()
	{
		return $this->priority;
	}
	
	public function setPriority($priority)
	{
		$this->priority = $priority;
	}
	
	public function getNice()
	{
		return $this->nice;
	}
	
	public function setNice($nice)
	{
		$this->nice = $nice;
	}
	
	public function getVirtualMemory()
	{
		return $this->virtualMemory;
	}
	
	public function setVirtualMemory($virtualMemory)
	{
		$this->virtualMemory = $virtualMemory;
	}
	
	public function getPhysicalMemory()
	{
		return $this->physicalMemory;
	}
	
	public function setPhysicalMemory($physicalMemory)
	{
		$this->physicalMemory = $physicalMemory;
	}
	
	public function getSharedMemory()
	{
		return $this->sharedMemory;
	}
	
	public function setSharedMemory($sharedMemory)
	{
		$this->sharedMemory = $sharedMemory;
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