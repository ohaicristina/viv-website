/**
 * Created by RaniaMasri on 1/21/15.
 */
/**
 * Created by RaniaMasri on 1/21/15.
 */

var margin = {t: 100, l: 100, b: 100, r: 100},
    width = $('.canvas').width() - margin.l - margin.r,
    height = $('.canvas').height() - margin.t - margin.b;

var svg = d3.select('.canvas')
    .append('svg')
    .attr('width', width + margin.l + margin.r)
    .attr('height', height + margin.t + margin.b)
    .append('g')
    .attr('transform', "translate(" + margin.l + "," + margin.t + ")");

var background = svg.append('rect')
    .attr('width', width)
    .attr('height', height);

var force = d3.layout.force()
        .size([width, height])
        .friction(0.8) //velocity decay
        .gravity(0.2) //
        .charge(-200) //negative value => repulsion
        .linkDistance(200) //weak geometric constraint
        .linkStrength(0)
    ;

//Variable to hold data
var data = [
    {type: "dominant", r: 30, color: "coral"},
    {type: "dominant", r: 50, color: "red"},
    {type: "dominant", r: 20, color: "green"},
    {type: "salient", r: 40, color: "blue"},
    {type: "salient", r: 15, color: "yellow"}
];

//Variables to hold selection
var peopleNodes;

//Multiple foci
var foci = {};
foci.m = {
    x: width / 3,
    y: height / 2
};
foci.f = {
    x: width * 2 / 3,
    y: height / 2
};


function nodes(err, d) {

    data = d;

    data.nodes.forEach(function (n) {
        var type = Math.floor(n.value) % 2 == 0 ? "dominant" : "salient";
        n.type = type;
    });

    force
        .nodes(data.nodes)
        .links(data.links)
        .on('tick', onTick)
        .start();

    draw(data);

    $('.control #multi').on('click', function (e) {
        e.preventDefault();

        force
            .stop()
            .gravity(0)
            .on('tick', onMultiFociTick)
            .start();
    });
    $('.control #single').on('click', function (e) {
        e.preventDefault();

        force
            .stop()
            .gravity(.1)
            .on('tick', onTick)
            .start();
    });
}

function draw(data) {
    //this function is now just for drawing

    //Join data to DOM with a key function
    peopleNodes = svg.selectAll('.node')
        .data(data.nodes, function (d) {
            return d.value;
        });

    //Enter set
    peopleNodes
        .enter()
        .append('g')
        .attr('class', 'node people')
        .call(force.drag)
        .each(function (d) {
            if (d.type == "dominant") {
            } else {
            }
            d3.select(this)
                .append('circle')
                .attr('r', 0)
        })

}

function onTick() {
    peopleNodes
        .attr('transform', function (d) {
            return 'translate(' + d.x + ',' + d.y + ')';
        })
    ;
}

function onMultiFociTick(e) {
    var k = 0.2 * e.alpha;

    data.nodes.forEach(function (n) {
        var focus = n.type == "dominant" ? foci.m : foci.f;
        n.x += (focus.x - n.x) * k;
        n.y += (focus.y - n.y) * k;
    });

    peopleNodes
        .attr('transform', function (d) {
            return 'translate(' + d.x + ',' + d.y + ')';
        });
}
