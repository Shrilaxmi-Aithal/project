
var express = require('express');
var bodyParser  =  require("body-parser");
var app = express();

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());



app.use(function (req, res, next) {
  console.log("middleware");
    // Website you wish to allow to connect
    res.header('Access-Control-Allow-Origin', '*');

    // Request methods you wish to allow
    res.header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

    // Request headers you wish to allow
    res.header('Access-Control-Allow-Headers', 'content-type');

    // Set to true if you need the website to include cookies in the requests sent
    // to the API (e.g. in case you use sessions)
    res.header('Access-Control-Allow-Credentials', true);

    // Pass to next layer of middleware
    next();
});

app.get('/api/users',function(req,res,next) {
  var MongoClient = require('mongodb').MongoClient;
  var returndata;
    MongoClient.connect("mongodb://localhost:27017/project", function(err, db) {
      if(err) {  console.log(err); }
      var collectionOne;

      var collection = db.collection('table1');
      db.collection("table1", function(err, collection) {
        collection.find().sort({count:-1}).toArray(function(err, result) {
            if (err) {
                throw err;
            } else {
              console.log("result"+JSON.stringify(result));
              res.send(JSON.stringify(result));                    
            }
        });
      });
    });
 });

app.post('/api/user',function(req ,res, next) {
  var data = req.body.first;
  console.log("daat post: "+data);
  var MongoClient = require('mongodb').MongoClient;
  MongoClient.connect("mongodb://localhost:27017/project", function(err, db) {
      if(err) {  console.log(err); }

      var collection = db.collection('table1');
      var doc1 = {'data':data, 'count':0};
     
      collection.insert(doc1);


  });
  console.log("inserted");

});

app.post("/api/updateuser", function(req, res, next) {
    var data = req.body.name;
    console.log("daat update: "+data);

    var MongoClient = require('mongodb').MongoClient;

    // Connect to the db
    MongoClient.connect("mongodb://localhost:27017/project", function(err, db) {
      if(err) {  console.log(err); }

      var collection = db.collection('table1');

      var doc1 = {'data':data};
      var finder  = collection.find(doc1,{_id:0,data:0},{count:1});
     console.log("fineder:"+(finder));

      var counter = {"count" :1};
      console.log("counter: ");
     
      collection.update(doc1,{$set:counter});
      res.send(200);

    });
    console.log("updated");

});
app.listen(8081);
