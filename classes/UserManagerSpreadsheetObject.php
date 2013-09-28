<?php

/**
 * This class helps to build a spreadsheet that can be downloaded and opened by applications like Microsoft Excel.
 *
 * @author Stefan Boonstra
 */
class UserManagerSpreadsheetObject
{
	protected $grid     = array();
	protected $fileName = "";

	/**
	 * @param string $fileName
	 */
	public function __construct($fileName)
	{
		$this->fileName = $fileName;
	}

	/**
	 * @returns string $fileName
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 * @param string $fileName
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;
	}

	/**
	 * @param int    $rowID
	 * @param int    $columnID
	 * @param string $value
	 */
	public function setCell($rowID, $columnID, $value)
	{
		if (!is_numeric($rowID) ||
			!is_numeric($columnID) ||
			strlen($value) <= 0)
		{
			return;
		}

		if (!isset($this->grid[$rowID]) ||
			!is_array($this->grid[$rowID]))
		{
			$this->grid[$rowID] = array();
		}

		$this->grid[$rowID][$columnID] = utf8_decode($value);
	}

	/**
	 * @return string $xlsFile
	 */
	public function generateFile()
	{
		$file = "";

		$rowID     = 0;
		$rowIDs    = array_keys($this->grid);
		$lastRowID = end($rowIDs);

		while ($rowID <= $lastRowID)
		{
			if (isset($this->grid[$rowID]) &&
				is_array($this->grid[$rowID]))
			{
				$columnID     = 0;
				$columnIDs    = array_keys($this->grid[$rowID]);
				$lastColumnID = end($columnIDs);

				while ($columnID <= $lastColumnID)
				{
					if (isset($this->grid[$rowID][$columnID]))
					{
						$file .= addslashes($this->grid[$rowID][$columnID]);
					}

					$file .= "\t";

					$columnID++;
				}
			}

			$file .= "\r\n";

			$rowID++;
		}

		return $file;
	}

	/**
	 * This function builds the spreadsheet file and prepares the headers to download it. No headers should be sent
	 * before calling this function.
	 *
	 * Make sure that the passed file name ends with an extension, for instance: fileName.xls
	 *
	 * @param string $fileName Optional, defaults to null. When left empty, the earlier defined or newly generated file name will be used.
	 */
	public function downloadFile($fileName = null)
	{
		if (!isset($fileName) ||
			strlen($fileName) <= 0)
		{
			if (strlen($this->fileName) > 0)
			{
				$fileName = $this->fileName;
			}
			else
			{
				$fileName = get_bloginfo("name") . " - " . date("Y-m-d h:i:s") . ".xls";
			}
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=\"" . addslashes($fileName) . "\"");

		echo $this->generateFile();

		die;
	}
}