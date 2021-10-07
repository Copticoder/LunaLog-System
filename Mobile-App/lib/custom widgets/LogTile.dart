import 'package:flutter/material.dart';

class LogTile extends StatelessWidget {
  final String logData;
  final String logTimeStamp;
  final String logKeyWords;
  final Function onClick;
  LogTile(
      {required this.logData,
      required this.logKeyWords,
      required this.logTimeStamp,
      required this.onClick});
  Widget build(BuildContext context) {
    return ListTile(
      onTap: () {
        onClick();
      },
      title: Text(logData),
      subtitle: Text(logKeyWords + ' ' + logTimeStamp),
    );
  }
}
