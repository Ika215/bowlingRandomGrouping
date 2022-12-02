<?php
// ボウリング企画でグループ分けをするためのCLIプログラム

/**
 * csvファイルを取得し、一行ずつ配列にいれる
 */
function getCsv($csvFileName) {
  // csvファイルを開く
  $csv = fopen($csvFileName, 'r');

  // csvファイルを内容をいれておく配列を用意
  $csvArray = [];

  // csvファイルを一行ずつ配列に収納
  while ($line = fgetcsv($csv)) {
    $array[] = $line;
    // $csvArray["id"] = $line[0];
    // $csvArray["name"] = $line[1];
    // $csvArray["status"] = $line[2];
  }

  // csvファイルを閉じる
  fclose($csv);

  // 収納した配列を返す
  return $array;
}

/**
 * 社員かインターン生か判定し、グループ分けのために分割する
 */
function sortStatus($array) {
  // 社員の配列
  $worker = [];
  // インターン生の配列
  $internStudent = [];
  // 元の配列の数を取得
  $countArray = count($array);

  // 社員かインターン生か振り分ける
  for ($i = 1; $i < $countArray; $i++) {
    if ($array[$i][2] === "1") {
      $worker[] = $array[$i];
    } else if ($array[$i][2] === "2") {
      $internStudent[] = $array[$i];
    }
  }

  // 社員とインターン生それぞれで返す
  return [$worker, $internStudent];
}

/**
 * 社員とインターン生別々でまずはグループ分けをする
 * 後ほど社員とインターン生が均等に分かれて欲しいから
 */
function groupingRandomByStatus ($array) {
  $groupOne = [];
  $groupTwo = [];
  $groupThree = [];
  $groupFour = [];

  $countArray = count($array);
  // 配列内をシャッフルし、ランダム性を持たせる
  shuffle($array);

  // ランダムでグループに振り分ける
  for ($i = 0; $i < $countArray; $i++) {
    if (count($groupOne) < "2") {
      $groupOne[] = $array[$i];
    } else if (count($groupTwo) < "2") {
      $groupTwo[] = $array[$i];
    } else if (count($groupThree) < "2") {
      $groupThree[] = $array[$i];
    } else {
      $groupFour[] = $array[$i];
    }
  }

  // 4つに分けたグループを1つの配列にして返す
  $sortedGroup = [$groupOne, $groupTwo, $groupThree, $groupFour];
  return $sortedGroup;
}

/**
 * 社員とインターン生が均等になるようにグループをきめる
 */
function sortRandomGroup($workerArray, $internStudentArray) {
  $groupOne = [$workerArray[0], $internStudentArray[1]];
  $groupTwo = [$workerArray[1], $internStudentArray[2]];
  $groupThree = [$workerArray[2], $internStudentArray[3]];
  $groupFour = [$workerArray[3], $internStudentArray[0]];

  // 4つのグループを1つの配列にして返す
  $group = [$groupOne, $groupTwo, $groupThree, $groupFour];
  return $group;
}

function main() {
  // casファイルの名前を入力
  echo "csvファイル名を入力してください";
  $stdin = trim(fgets(STDIN));

  // csvファイルの中身を配列にいれる
  $csvArray = getCsv($stdin);

  // 社員かインターン生で分ける
  list($worker, $internStudent) = sortStatus($csvArray);

  // 社員とインターン生それぞれでグループ分けを先に行う
  $sortedWorker = groupingRandomByStatus($worker);
  $sortedInternStudent = groupingRandomByStatus($internStudent);

  // 社員とインターン生のグループをくっつけて4つのグループを作製
  $group = sortRandomGroup($sortedWorker, $sortedInternStudent);

  // 下の処理は関数にすればもっと簡単にできる
  echo 'グループ分けは以下の通りです！'."\n";
  echo '1レーン'. "\n";
  echo $group[0][0][0][1];
  echo $group[0][0][1][1];
  echo $group[0][1][0][1];
  echo $group[0][1][1][1];
  echo "\n";

  echo '2レーン'. "\n";
  echo $group[1][0][0][1];
  echo $group[1][0][1][1];
  echo $group[1][1][0][1];
  echo $group[1][1][1][1];
  echo "\n";

  echo '3レーン'. "\n";
  echo $group[2][0][0][1];
  echo $group[2][0][1][1];
  echo $group[2][1][0][1];
  echo "\n";

  echo '4レーン'. "\n";
  echo $group[3][0][0][1];
  echo $group[3][1][0][1];
  echo $group[3][1][1][1];
  echo "\n";
}

main();

?>
