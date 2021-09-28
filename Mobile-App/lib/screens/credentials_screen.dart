import 'package:flutter/material.dart';
import 'blog_screen.dart';

class CredentialsPage extends StatelessWidget {
  changeForm() {}
  String username = '';
  String password = '';
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                'Nasa Blog 🚀',
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 50,
                ),
              ),
              Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 10, horizontal: 20),
                child: TextField(
                  onChanged: (String text) {
                    username = text;
                  },
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
                  onChanged: (String text) {
                    password = text;
                  },
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
              Column(
                children: [
                  TextButton(
                      onPressed: () {
                        Navigator.pushNamed(context, '/second');
                      },
                      child: Text(
                        'Login',
                        style: TextStyle(fontSize: 20),
                      )),
                  TextButton(
                      onPressed: changeForm,
                      child: Text(
                        'Sign Up ',
                        style: TextStyle(fontSize: 20),
                      ))
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }
}
