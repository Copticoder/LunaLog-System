import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:nasa_blog/custom%20widgets/LogTile.dart';
import 'package:nasa_blog/model/data.dart';

import '../log_post_details.dart';

class OtherUserLogs extends StatelessWidget {
  final String userName;
  final String userID;
  OtherUserLogs(this.userName, this.userID);
  @override
  Widget build(BuildContext context) {
    final userLog = AllUsersLogs();
    return Scaffold(
      appBar: AppBar(
        title: Text('$userName Logs'),
      ),
      body: StreamBuilder(
          stream: userLog.getLogs(),
          builder: (context, AsyncSnapshot snapshot) {
            if (snapshot.data == null) {
              return Center(
                child: CircularProgressIndicator(),
              );
            } else {
              List allLogsSorted =
                  AllMainUserLogsList(userID, snapshot).getAllLogs();
              return ListView.builder(
                  shrinkWrap: true,
                  scrollDirection: Axis.vertical,
                  itemCount: allLogsSorted.length,
                  itemBuilder: (BuildContext context, int index) {
                    return LogTile(
                        logData: allLogsSorted[index]['meta_data'],
                        logKeyWords: allLogsSorted[index]['tags'],
                        logTimeStamp: allLogsSorted[index]['posted_at'],
                        onClick: () {
                          Navigator.push(
                              context,
                              MaterialPageRoute(
                                  builder: (context) =>
                                      LogPostDetail(allLogsSorted[index])));
                        });
                  });
            }
          }),
    );
  }
}
