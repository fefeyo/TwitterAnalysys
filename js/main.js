$(function(){
  getTweet();
});

function getTweet(){
  $('#search_button').click(function(){
    var dataSet = {
      'name': 'ツイート',
      'children':[
      {
        'name': 'ツイート',
        'children': []
      },
      ]
    };
    $('#search_button').css('display', 'none');
    $('#loading').fadeIn();
    $.get(
      '../php/getTweet.php',
      {
        search_word: $('#search_form').val()
      },
      function(data){
        $('#loading').fadeOut();
        var result = $.parseJSON(data);
        $.each(result, function(index, val){
          if(val.name.length > 15){
            val.name = val.name.substring(0, 15);
          }
          dataSet.children[0].children.unshift({
            'name': val.name,
            'score': val.score
          });
        });
        createBubbleChart(dataSet);
        $('#result').css('display', 'block')
        $('#search_button').css('display', 'inline');
      });
  });
}

function createBubbleChart(data){
  console.log(data);
var diameter = 1000; // 一番大きい円のサイズ
var svg = d3.select("#result")
.append('svg')
.attr('width', '1000')
.attr('height', '1000')
.attr('id', 'canvas');

var bubble = d3.layout.pack()
.size([diameter, diameter])
    .padding('1.5'); // 表示サイズを指定
var grp = svg.selectAll("g")    // グループを対象にする
    .data(bubble.nodes(classes(data))) // データを読み込む
    .enter()
    .append("g")
    .attr("transform", function(d) {    // 円のX,Y座標を設定
      return "translate(" + d.x + "," + d.y + ")";
    });



    grp.append("circle")    // 円を生成する
    .attr("r", function(d){ return d.r;})
    .attr('stroke-width', '0');

    grp.attr("fill", function(d,i){    // 塗りの色を指定
      if(i == 0) return;
      if(d.value < 100){
        return '#95a5a6';
      }else if(d.value >= 100 && d.value < 200){
        return '#3498db';
      }else if(d.value >= 200 && d.value < 300){
        return '#f1c40f';
      }else{
        return '#e74c3c';
      }
    });

    grp.append("text")  // 文字を生成する
        .attr("font-size", "12px")   // 文字のサイズを指定する
        .attr("fill", 'white')  // 文字の塗りの色を指定する
        .attr('stroke', 'white')
        .attr('stroke-width', '0')
        .style("text-anchor", "middle") // 円の座標の中央から表示するようにする
        .text(function(d) { return d.className; } ); // データの中のclassNameが地区名なので、それを出力
      }

// 階層化されたJSONデータをフラット化する (D3.js本家のを少し変更して利用)
function classes(root) {
      var classes = [];
      function recurse(name, node) {
            if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
                else classes.push({packageName: name, className: node.name, value: node.score});
      }
      recurse(null, root);
      return {children: classes};
}