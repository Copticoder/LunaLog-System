import 'package:flutter/material.dart';


class OtherLogs extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Colors.white,
        iconTheme: IconThemeData(color: Colors.black),
        title: Text(
          "Niel Armstrong's logs",
          style: TextStyle(color: Colors.black),
        ),
      ),
      body: SafeArea(
          minimum: EdgeInsets.all(10),
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 30),
            child: Center(
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundImage: AssetImage('assets/images/niel.jpg'),
                  ),
                  SizedBox(
                    height: 10,
                  ),
                  Text('Niel Armstrong'),
                  SizedBox(
                    height: 30,
                  ),
                  // ListView.separated(
                  //   shrinkWrap: true,
                  //   itemCount: data.length,
                  //   itemBuilder: (context, index) {
                  //     final post = data[index];
                  //     return LogTile(
                  //       logKeyWords: ["Lol", "XD", "LMAOOASDOASAFOASGOAD"],
                  //       logTimeStamp: post.title,
                  //       onClick: () {
                  //         Navigator.push(
                  //             context,
                  //             MaterialPageRoute(
                  //                 builder: (context) => LogPostDetail()));
                  //       },
                  //     );
                  //   },
                  //   separatorBuilder: (context, index) =>
                  //       Divider(thickness: 2, height: 5),
                  // )
                ],
              ),
            ),
          )),
    );
  }
}
