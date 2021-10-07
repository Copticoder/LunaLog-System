import 'package:flutter/material.dart';
import 'package:nasa_blog/model/data.dart';
import 'package:nasa_blog/screens/logs_screens/log_screen.dart';

// ignore: must_be_immutable
class CredentialsPage extends StatelessWidget {
  TextEditingController email = TextEditingController();
  TextEditingController password = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                'LunaLogs 🚀',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 50,
                ),
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 10, horizontal: 20),
                child: TextField(
                  controller: email,
                  decoration: InputDecoration(
                    hintText: 'username',
                    filled: true,
                    fillColor: Colors.grey[200],
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(Radius.circular(10)),
                      borderSide: BorderSide.none,
                    ),
                    prefixIcon: Icon(Icons.supervised_user_circle),
                  ),
                ),
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 10, horizontal: 20),
                child: TextField(
                  enableSuggestions: false,
                  autocorrect: false,
                  obscureText: true,
                  controller: password,
                  decoration: InputDecoration(
                    hintText: 'password',
                    filled: true,
                    fillColor: Colors.grey[200],
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(Radius.circular(10)),
                      borderSide: BorderSide.none,
                    ),
                    prefixIcon: Icon(Icons.lock),
                  ),
                ),
              ),
              TextButton(
                  onPressed: () async {
                    final user = User();
                    var userData = await user.login(
                        email.text.trimRight(), password.text.trimRight());
                    if (userData['message'] == 'invalid credentials!') {
                      return showDialog(
                          context: context,
                          builder: (BuildContext context) {
                            return AlertDialog(
                              title: Text("Error"),
                              content: Text(userData['message']),
                              actions: [
                                TextButton(
                                    onPressed: () {
                                      Navigator.of(context).pop();
                                    },
                                    child: Text('Ok'))
                              ],
                            );
                          });
                    } else {
                      Navigator.push(
                          context,
                          MaterialPageRoute(
                              builder: (context) => LogsScreen(
                                  userData['user_data']['user_id'])));
                    }
                  },
                  child: Text(
                    'LOGIN',
                    style: TextStyle(fontSize: 20),
                  )),
            ],
          ),
        ),
      ),
    );
  }
}
