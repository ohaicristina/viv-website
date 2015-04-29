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
        .friction(0.6) //velocity decay
        .gravity(0.1) //
        .charge(-300) //negative value => repulsion
        .linkDistance(0) //weak geometric constraint
        .linkStrength(0)
    ;

//Variable to hold data
var data;

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


queue()
    .defer(d3.json, 'data/force.json')
    .await(function (err, d) {

        data = d;

        data.nodes.forEach(function (n) {
            var gender = Math.floor(n.value) % 2 == 0 ? "m" : "f";
            n.gender = gender;
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
        })
        $('.control #single').on('click', function (e) {
            e.preventDefault();

            force
                .stop()
                .gravity(.1)
                .on('tick', onTick)
                .start();
        });

    });

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
            if (d.gender == "m") {
            } else {
            }
            d3.select(this)
                .append('circle')
                .attr('r', 22)
                .style('fill', 'gray');
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
        var focus = n.gender == "m" ? foci.m : foci.f;
        n.x += (focus.x - n.x) * k;
        n.y += (focus.y - n.y) * k;
    });

    peopleNodes
        .attr('transform', function (d) {
            return 'translate(' + d.x + ',' + d.y + ')';
        });
}
