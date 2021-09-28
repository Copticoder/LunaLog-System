import 'package:flutter/material.dart';
import 'package:nasa_blog/screens/blog_screen.dart';
import 'package:nasa_blog/screens/credentials_screen.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      theme: ThemeData(fontFamily: 'Nasalization'),
      initialRoute: '/first',
      routes: {
        '/first': (context) => CredentialsPage(),
        '/second': (context) => BlogScreen(),
      },
    );
  }
}
