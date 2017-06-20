<!DOCTYPE html>
<html>
	<head>
		<title>Word Search Solver</title>
	</head>
<?php
$run = false;
if (isset($_POST['a']) && isset($_POST['c']))
	$run = true;
?>
	<body>
		<form method="post" action="wordsearchsolver.php">
		Words to Find<br>seperate each word by a newline
		<br><textarea name="a" cols="25" rows="10"><?php if($run == true){ echo $_POST['a']; } ?></textarea><br><br>
		The Puzzle<br>seperate each word by a newline
		<br><textarea name="c" cols="25" rows="10"><?php if($run == true){ echo $_POST['c']; } ?></textarea><br><br>
		<input type="submit" value="Submit">
		</form>
		<br>
	</body>
</html>

<?php
if ($run == true)
{
$words = $_POST['a'];
$wordlist = explode("\r\n", $words);

$lines = $_POST['c'];
$lines = explode("\r\n", $lines);
$table = array();
foreach ($lines as $line)
{
	if(strlen($line) != strlen($lines[0]))
	{
		die("ERROR: Lines aren't the same length." . $line);
	}
	array_push($table, str_split($line));
	//echo $line . ":" . strlen($line) . "<br>";
}

//var_dump($table);

echo "<table>";
echo "<tr>";
echo "<th>word</th>";
echo "<th>direction</th>";
echo "<th>line</th>";
echo "<th>row</th>";
echo "<th>col</th>";
echo "</tr>";

function SE ($sa, $la)
{
	foreach ($sa as $word) //each word do this...
	{
		for ($r = 0; $r < count($la) + count($la[0]) - 1; $r++) //r=#runs=len(col)+len(row)-1
		{
			$j = count($la[0]) - 1 - $r; //j=column coordinate
			$clonej = $j;
			$i = 0; //row coordinate
			$clonei = $i;
			if ($j < 0) //if pointer is out of bounds int the first row to the left of the table...
			{
				//System.out.println(r - letteray.length + 1);
				$i += $r - count($la[0]) + 1; //i goes right...
				$clonei = $i;
				$j += $r - count($la[0]) + 1; //j goes down...
				$clonej = $j;
				//until (i,j) is back in the table
			}
			//System.out.println(i + ":" + j);
			$line = "" . $la[$i][$j]; //(i,j) starts at uppermost rightmost, ends at bottommost leftmost
			while ($i < count($la) - 1 && $j < count($la[0]) - 1) //while (i,j) is not in the right boundary or bottom boundary
			{
				$i++;
				$j++;
				//mv (i,j) diagonally down right by one
				//System.out.println(i + "|" + j);
				$line .= $la[$i][$j]; //append (i,j) char to current diagonal line array
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>southeast</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($clonei + strpos($line,$word) + 1) . "</td>"; //clonei is zero-indexed, clonei+1 is one-indexed, this needs to plus the shift right factor
				echo "<td>" . ($clonej + strpos($line,$word) + 1) . "</td>"; //clonej is 0-indexed, clonej+1 is 1-indexed, plus the index in the current diagonal line array aka the shift factor down
				echo "</tr>";
			}
		}
	}
}

function N ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($j = 0; $j < count($la[0]); $j++)
		{
			$line = "";
			for ($i = count($la) - 1; $i >= 0; $i--)
			{
				$line .= $la[$i][$j];
				//System.out.print(line);
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>north" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . (count($la) - strpos($line,$word)) . "</td>";
				echo "<td>" . ($j + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function S ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($j = 0; $j < count($la[0]); $j++)
		{
			$line = "";
			for ($i = 0; $i < count($la); $i++)
			{
				$line .= $la[$i][$j];
				//System.out.print(line);
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>south" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . (strpos($line,$word) + 1) . "</td>";
				echo "<td>" . ($j + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function E ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($i = 0; $i < count($la); $i++)
		{
			$line = "";
			for ($j = 0; $j < count($la[$i]); $j++)
			{
				$line .= $la[$i][$j];
				//System.out.print(line);
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>east" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($i + 1) . "</td>";
				echo "<td>" . (strpos($line,$word) + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function W ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($i = 0; $i < count($la); $i++)
		{
			$line = "";
			for ($j = count($la[$i]) - 1; $j >= 0; $j--)
			{
				$line .= $la[$i][$j];
				//System.out.print(line);
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>west" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($i + 1) . "</td>";
				echo "<td>" . (count($la[$i]) - strpos($line,$word)) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function NE ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($r = 0; $r < count($la) + count($la[0]) - 1; $r++)
		{
			$i = $r;
			$clonei = $r;
			$j = 0;
			$clonej = $j;
			if ($i >= count($la))
			{
				$i -= $r - count($la) + 1;
				$clonei = $i;
				$j += $r - count($la) + 1;
				$clonej = $j;
			}
			//System.out.println(i + ":" + j);
			$line = "" . $la[$i][$j];
			while ($i > 0 && $j < count($la[0]) - 1)
			{
				$i--;
				$j++;
				$line .= $la[$i][$j];
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>northeast" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($clonei - strpos($line,$word) + 1) . "</td>";
				echo "<td>" . ($clonej + strpos($line,$word) + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function NW ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($r = 0; $r < count($la) + count($la[0]) - 1; $r++)
		{
			$i = $r;
			$clonei = $r;
			$j = count($la[0]) - 1;
			$clonej = $j;
			if ($i >= count($la))
			{
				//System.out.println(r - letteray.length + 1);
				$i -= $r - count($la) + 1;
				$clonei = $i;
				$j -= $r - count($la) + 1;
				$clonej = $j;
			}
			//System.out.println(i + ":" + j);
			$line = "" . $la[$i][$j];
			while ($i > 0 && $j > 0)
			{
				$i--;
				$j--;
				//System.out.println(i + "|" + j);
				$line .= $la[$i][$j];
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>northwest" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($clonei - strpos($line,$word) + 1) . "</td>";
				echo "<td>" . ($clonej - strpos($line,$word) + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

function SW ($sa, $la)
{
	foreach ($sa as $word)
	{
		for ($r = 0; $r < count($la) + count($la[0]) - 1; $r++)
		{
			$j = $r;
			$clonej = $r;
			$i = 0;
			$clonei = $i;
			if ($j >= count($la[0]))
			{
				$j -= $r - count($la[0]) + 1;
				$clonej = $j;
				$i += $r - count($la[0]) + 1;
				$clonei = $i;
			}
			
			$line = "" . $la[$i][$j];
			while ($j > 0 && $i < count($la) - 1)
			{
				$j--;
				$i++;
				$line .= $la[$i][$j];
			}
			if (strpos($line,$word) !== false)
			{
				echo "<tr>";
				echo "<td>" . $word . "</td>";
				echo "<td>southwest" . "</td>";
				echo "<td>" . $line . "</td>";
				echo "<td>" . ($clonei + strpos($line,$word) + 1) . "</td>";
				echo "<td>" . ($clonej - strpos($line,$word) + 1) . "</td>";
				echo "</tr>";
			}
		}
	}
}

N($wordlist,$table);
NE($wordlist,$table);
E($wordlist,$table);
SE($wordlist,$table);
S($wordlist,$table);
SW($wordlist,$table);
W($wordlist,$table);
NW($wordlist,$table);

echo "</table>";
}
?>