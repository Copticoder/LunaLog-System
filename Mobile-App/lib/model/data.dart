import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class User {
  Future login(String email, String password) async {
    var urlLogin =
        Uri.parse("https://copticon.000webhostapp.com/api/user/login");
    var data = {"email": email, "password": password};
    final response = await http.post(urlLogin, body: data);
    var userData = json.decode(response.body);
    return userData;
  }
}

class AllUsersLogs {
  List userLogsData = [];
  Stream getLogs() => Stream.periodic(Duration(seconds: 10))
      .asyncMap((_) => getLog())
      .asBroadcastStream();

  Future getLog() async {
    var urlLog = Uri.parse("https://copticon.000webhostapp.com/api/users/logs");
    var response = await http.get(urlLog);
    Map logsData = json.decode(response.body);
    // print(logsData.keys.toList());
    return logsData;
  }
}

class AllMainUserLogsList {
  final String uID;
  final AsyncSnapshot snapShot;
  AllMainUserLogsList(this.uID, this.snapShot);
  getAllLogs() {
    List mainUserLogs = [];
    if (snapShot.data[uID]['text_logs'] != null) {
      mainUserLogs.addAll(snapShot.data[uID]['text_logs']);
    }
    if (snapShot.data[uID]['audio_logs'] != null) {
      mainUserLogs.addAll(snapShot.data[uID]['audio_logs']);
    }
    if (snapShot.data[uID]['img_logs'] != null) {
      mainUserLogs.addAll(snapShot.data[uID]['img_logs']);
    }
    mainUserLogs.sort((a, b) => DateTime.parse(b['posted_at'])
        .compareTo(DateTime.parse(a['posted_at'])));
    // print(mainUserLogs);
    return mainUserLogs;
  }
}
