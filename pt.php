<?php
// api goes here
// example url: https://vikramacharya.com/api/pt.php?mode=names&elements=H,Ar,Hf
require('names.php');
require('numbers.php');
require('electrons.php');

$fullResult = ["error" => null];

if(isset($_GET["mode"])) {
    foreach(explode(',', $_GET["mode"]) as $mode) {
        switch($mode) {
        case "names":
            if(isset($_GET["elements"])) {
                $errorElements = [];
                // gets the element symbols from the URL
                $elements = explode(',', $_GET["elements"]);
                foreach ($elements as $index => $symbol) {
                    if(in_array(ucfirst(strtolower($symbol)), array_keys($names))) {
                        $elements[$index] = ucfirst(strtolower($symbol));
                    } else {
                        unset($elements[$index]);
                        array_push($errorElements, $symbol);
                    }
                }
            } else {
                // if none were specified, just do all of them
                $elements = array_keys($names);
            }
            $result = [];
            // gets the names from $names and puts them in the final array
            foreach ($elements as $element) {
                $name = $names[$element];
                $result[$element] = $name;
            }
            // pops an error message in the $result
            if(isset($errorElements)) {
                foreach ($errorElements as $error) {
                    $result[$error] = "Invalid element!";
                }
            }
            $fullResult[$mode] = $result;
            break;
        case "orbitals":
        case "electrons":
            if(isset($_GET["elements"])) {
                $errorElements = [];
                // gets the element symbols from the URL
                $elements = explode(',', $_GET["elements"]);
                foreach ($elements as $index => $symbol) {
                    if(in_array(ucfirst(strtolower($symbol)), array_keys($electrons))) {
                        $elements[$index] = ucfirst(strtolower($symbol));
                    } else {
                        unset($elements[$index]);
                        array_push($errorElements, $symbol);
                    }
                }
            } else {
                // if none were specified, just do all of them
                $elements = array_keys($electrons);
            }
            $result = [];
            $atomicNumbers = [];
            // converts the symbols to atomic numbers (i could probably just use array_push() here, but i don't really care)
            foreach ($elements as $index => $symbol) {
                $atomicNumbers[$index] = $numbers[$symbol]["atomic"];
            }
            // calculates configurations & gets element blocks if requested
            foreach ($elements as $index => $element) {
                $result[$element]["config"] = electron_config($atomicNumbers[$index]);
                $result[$element]["short"] = electron_config($atomicNumbers[$index], true);
                if(isset($_GET["showBlocks"])) {
                    if($_GET["showBlocks"]) {
                        $result[$element]["block"] = $electrons[$element]["block"];
                    }
                }
            }
            // pops an error message in the $result
            if(isset($errorElements)) {
                foreach ($errorElements as $error) {
                    $result[$error] = "Invalid element!";
                }
            }
            $fullResult[$mode] = $result;
            break;
        case "numbers":
            if(isset($_GET["elements"])) {
                $errorElements = [];
                // gets the element symbols from the URL
                $elements = explode(',', $_GET["elements"]);
                foreach ($elements as $index => $symbol) {
                    if(in_array(ucfirst(strtolower($symbol)), array_keys($electrons))) {
                        $elements[$index] = ucfirst(strtolower($symbol));
                    } else {
                        unset($elements[$index]);
                        array_push($errorElements, $symbol);
                    }
                }
            } else {
                // if none were specified, just do all of them
                $elements = array_keys($numbers);
            }
            $result = [];
            // gets atomic numbers and possibly masses from $numbers, passes them to $resultsp
            foreach ($elements as $element) {
                $result[$element]["atomic"] = $numbers[$element]["atomic"];
                if(isset($_GET["mass"])) {
                    if($_GET["mass"]) {
                        $result[$element]["mass"] = $numbers[$element]["mass"];
                    }
                }
            }
            // pops an error message in the $result
            if(isset($errorElements)) {
                foreach ($errorElements as $error) {
                    $result[$error] = "Invalid element!";
                }
            }
            $fullResult[$mode] = $result;
            break;
        case "dankmemes":
            $fullResult[$mode] = [69 => "( ͡° ͜ʖ ͡°)"];
            break;
        default:
            // invalid mode? echo an error.
            $fullResult["error"] = "Invalid mode. Please try again!";
            break;
        }
    }       
} else {
	// no modes? echo an error.
	$fullResult["error"] = "No mode specified. Please try again!";
}

// echo everything that's been set.
header('Content-Type: application/json');
echo json_encode($fullResult);