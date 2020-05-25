define("ekyna-verified-reviews/templates", ["twig"], function(Twig) {
var templates = {};
templates["reviews.html.twig"] = Twig.twig({ id: "reviews.html.twig", data: [{"type":"logic","token":{"type":"Twig.logic.type.spaceless","match":["spaceless"],"output":[{"type":"raw","value":"<div>\n"},{"type":"logic","token":{"type":"Twig.logic.type.for","keyVar":null,"valueVar":"r","expression":[{"type":"Twig.expression.type.variable","value":"reviews","match":["reviews"]}],"output":[{"type":"raw","value":"    "},{"type":"logic","token":{"type":"Twig.logic.type.if","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"first"}],"output":[{"type":"raw","value":"<div class=\"row\">"}]}},{"type":"logic","token":{"type":"Twig.logic.type.elseif","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"index0"},{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"columns"},{"type":"Twig.expression.type.operator.binary","value":"%","precidence":5,"associativity":"leftToRight","operator":"%"},{"type":"Twig.expression.type.number","value":0,"match":["0",null]},{"type":"Twig.expression.type.operator.binary","value":"==","precidence":9,"associativity":"leftToRight","operator":"=="}],"output":[{"type":"raw","value":"<div class=\"row\">"}]}},{"type":"raw","value":"    <div class=\"col-md-"},{"type":"output","stack":[{"type":"Twig.expression.type.number","value":12,"match":["12",null]},{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"columns"},{"type":"Twig.expression.type.operator.binary","value":"/","precidence":5,"associativity":"leftToRight","operator":"/"}]},{"type":"raw","value":"\" data-index0=\""},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"index0"}]},{"type":"raw","value":"\" data-index=\""},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"index"}]},{"type":"raw","value":"\">\n        <div class=\"verified-review\">\n            <p>\n                <span>\n                    "},{"type":"logic","token":{"type":"Twig.logic.type.if","stack":[{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"name"},{"type":"Twig.expression.type.test","filter":"empty"}],"output":[{"type":"raw","value":"                        "},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"trans"},{"type":"Twig.expression.type.key.period","key":"anon"},{"type":"Twig.expression.type.filter","value":"replace","match":["|replace","replace"],"params":[{"type":"Twig.expression.type.parameter.start","value":"(","match":["("]},{"type":"Twig.expression.type.object.start","value":"{","match":["{"]},{"type":"Twig.expression.type.operator.binary","value":":","precidence":16,"associativity":"rightToLeft","operator":":","key":"{date}"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"date"},{"type":"Twig.expression.type.object.end","value":"}","match":["}"]},{"type":"Twig.expression.type.parameter.end","value":")","match":[")"],"expression":false}]}]},{"type":"raw","value":"\n                    "}]}},{"type":"logic","token":{"type":"Twig.logic.type.else","match":["else"],"output":[{"type":"raw","value":"                        "},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"trans"},{"type":"Twig.expression.type.key.period","key":"info"},{"type":"Twig.expression.type.filter","value":"replace","match":["|replace","replace"],"params":[{"type":"Twig.expression.type.parameter.start","value":"(","match":["("]},{"type":"Twig.expression.type.object.start","value":"{","match":["{"]},{"type":"Twig.expression.type.operator.binary","value":":","precidence":16,"associativity":"rightToLeft","operator":":","key":"{name}"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"name"},{"type":"Twig.expression.type.comma"},{"type":"Twig.expression.type.operator.binary","value":":","precidence":16,"associativity":"rightToLeft","operator":":","key":"{date}"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"date"},{"type":"Twig.expression.type.object.end","value":"}","match":["}"]},{"type":"Twig.expression.type.parameter.end","value":")","match":[")"],"expression":false}]}]},{"type":"raw","value":"\n                    "}]}},{"type":"raw","value":"                </span>\n                <span class=\"verified-review-rate\" title=\""},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"trans"},{"type":"Twig.expression.type.key.period","key":"rate"},{"type":"Twig.expression.type.filter","value":"replace","match":["|replace","replace"],"params":[{"type":"Twig.expression.type.parameter.start","value":"(","match":["("]},{"type":"Twig.expression.type.object.start","value":"{","match":["{"]},{"type":"Twig.expression.type.operator.binary","value":":","precidence":16,"associativity":"rightToLeft","operator":":","key":"{rate}"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"rate"},{"type":"Twig.expression.type.object.end","value":"}","match":["}"]},{"type":"Twig.expression.type.parameter.end","value":")","match":[")"],"expression":false}]}]},{"type":"raw","value":"\">\n                    <i>"},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"trans"},{"type":"Twig.expression.type.key.period","key":"rate"},{"type":"Twig.expression.type.filter","value":"replace","match":["|replace","replace"],"params":[{"type":"Twig.expression.type.parameter.start","value":"(","match":["("]},{"type":"Twig.expression.type.object.start","value":"{","match":["{"]},{"type":"Twig.expression.type.operator.binary","value":":","precidence":16,"associativity":"rightToLeft","operator":":","key":"{rate}"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"rate"},{"type":"Twig.expression.type.object.end","value":"}","match":["}"]},{"type":"Twig.expression.type.parameter.end","value":")","match":[")"],"expression":false}]}]},{"type":"raw","value":"</i>\n                    <i style=\"width: "},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"width"},{"type":"Twig.expression.type.number","value":5,"match":["5",null]},{"type":"Twig.expression.type.operator.binary","value":"/","precidence":5,"associativity":"leftToRight","operator":"/"},{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"rate"},{"type":"Twig.expression.type.operator.binary","value":"*","precidence":5,"associativity":"leftToRight","operator":"*"}]},{"type":"raw","value":"px\"></i>\n                </span>\n            </p>\n            <p>"},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"content"},{"type":"Twig.expression.type.filter","value":"raw","match":["|raw","raw"]}]},{"type":"raw","value":"</p>\n            "},{"type":"logic","token":{"type":"Twig.logic.type.for","keyVar":null,"valueVar":"c","expression":[{"type":"Twig.expression.type.variable","value":"r","match":["r"]},{"type":"Twig.expression.type.key.period","key":"comments"}],"output":[{"type":"raw","value":"            <p class=\"comment"},{"type":"logic","token":{"type":"Twig.logic.type.if","stack":[{"type":"Twig.expression.type.variable","value":"c","match":["c"]},{"type":"Twig.expression.type.key.period","key":"customer"},{"type":"Twig.expression.type.operator.unary","value":"not","precidence":3,"associativity":"rightToLeft","operator":"not"}],"output":[{"type":"raw","value":" website"}]}},{"type":"raw","value":"\">\n                <em>("},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"c","match":["c"]},{"type":"Twig.expression.type.key.period","key":"date"}]},{"type":"raw","value":")</em> "},{"type":"output","stack":[{"type":"Twig.expression.type.variable","value":"c","match":["c"]},{"type":"Twig.expression.type.key.period","key":"message"},{"type":"Twig.expression.type.filter","value":"raw","match":["|raw","raw"]}]},{"type":"raw","value":"\n            </p>\n            "}]}},{"type":"raw","value":"        </div>\n    </div>\n    "},{"type":"logic","token":{"type":"Twig.logic.type.if","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"last"}],"output":[{"type":"raw","value":"</div>"}]}},{"type":"logic","token":{"type":"Twig.logic.type.elseif","stack":[{"type":"Twig.expression.type.variable","value":"loop","match":["loop"]},{"type":"Twig.expression.type.key.period","key":"index"},{"type":"Twig.expression.type.variable","value":"config","match":["config"]},{"type":"Twig.expression.type.key.period","key":"columns"},{"type":"Twig.expression.type.operator.binary","value":"%","precidence":5,"associativity":"leftToRight","operator":"%"},{"type":"Twig.expression.type.number","value":0,"match":["0",null]},{"type":"Twig.expression.type.operator.binary","value":"==","precidence":9,"associativity":"leftToRight","operator":"=="}],"output":[{"type":"raw","value":"</div>"}]}}]}},{"type":"raw","value":"</div>\n"}]}}] });
return templates;
});
