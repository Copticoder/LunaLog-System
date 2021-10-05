import 'package:flutter/material.dart';
import 'package:nasa_blog/custom%20widgets/LogTile.dart';
import 'package:nasa_blog/custom%20widgets/blog_post_tile.dart';
import 'package:nasa_blog/model/data.dart';
import 'package:nasa_blog/screens/log_post_details.dart';

import 'other_user_logs_screen.dart';

class AllUsersLogScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final userLog = AllUsersLogs();
    return Column(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          'All Users',
          style: TextStyle(
            color: Colors.black,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
          textAlign: TextAlign.left,
        ),
        const SizedBox(
          height: 20,
        ),
        StreamBuilder(
            stream: userLog.getLogs(),
            builder: (context, AsyncSnapshot snapshot) {
              if (snapshot.data == null) {
                return CircularProgressIndicator();
              }
              final usersKeys = snapshot.data.keys.toList();
              final usersList = snapshot.data.values.toList();
              return ListView.separated(
                scrollDirection: Axis.vertical,
                shrinkWrap: true,
                itemCount: snapshot.data.length,
                itemBuilder: (context, index) {
                  return BlogPostTile(
                      author: usersList[index]['user_name'],
                      image: 'assets/images/niel.jpg',
                      onClick: () {
                        Navigator.push(
                            context,
                            MaterialPageRoute(
                                builder: (context) => OtherUserLogs(
                                    usersList[index]['user_name'],
                                    usersKeys[index].toString())));
                      });
                },
                separatorBuilder: (context, index) => Divider(),
              );
            }),
        const SizedBox(
          height: 20,
        ),
      ],
    );
  }
}

class MainUserLogScreen extends StatelessWidget {
  final String userID;
  MainUserLogScreen(this.userID);
  @override
  Widget build(BuildContext context) {
    final userLog = AllUsersLogs();
    return Expanded(
      child: Container(
        child: Column(
          children: [
            Text(
              'My Logs',
              style: TextStyle(
                color: Colors.black,
                fontWeight: FontWeight.bold,
                fontSize: 18,
              ),
              textAlign: TextAlign.left,
            ),
            StreamBuilder(
                stream: userLog.getLogs(),
                builder: (context, AsyncSnapshot snapshot) {
                  if (snapshot.data == null) {
                    return Center(
                      child: CircularProgressIndicator(),
                    );
                  } else {
                    List allLogsSorted =
                        AllMainUserLogsList(userID, snapshot).getAllLogs();
                    return Expanded(
                      child: ListView.builder(
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
                                          builder: (context) => LogPostDetail(
                                              allLogsSorted[index])));
                                });
                          }),
                    );
                  }
                }),
          ],
        ),
      ),
    );
  }
}
