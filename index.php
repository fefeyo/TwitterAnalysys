<!DOCTYPE html>
<html lang="ja">
<head>
    <title>マルチメディア最終課題</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

    <div class="container">
        <header id="header">
            マルチメディアさいしゅうかだい！<br />
            ツイッターよくデるワードケンサク
        </header><!-- /header -->
        <div id="loading"><img src="img/load.gif" height="80" width="80"></div>
        <section id="search">
            <label for="search_word">ケンサクしたいワードをニュウリョクしてね</label><br />
            <input type="text" name="search_word" placeholder="例:艦これ" id="search_form">
            <button class="btn btn-danger btn-lg" id="search_button">ケンサクカイシ</button>
        </section>

        <div id="result">
        </div>

        <footer id="footer">
            作成:E14C4058　阪田祐宇
        </footer>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
        <script src="js/main.js"></script>
    </div>
</body>
</html>