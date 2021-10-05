import 'package:flutter/material.dart';
import 'package:nasa_blog/screens/logs_screens/userlogs_&_alluser.dart';

// ignore: must_be_immutable
class LogsScreen extends StatelessWidget {
  final String userID;
  LogsScreen(this.userID);
  var selectedIndex = ValueNotifier(0);

  @override
  Widget build(BuildContext context) {
    final tabs = [AllUsersLogScreen(), MainUserLogScreen(userID)];
    return Scaffold(
      resizeToAvoidBottomInset: false,
      appBar: AppBar(
        iconTheme: IconThemeData(color: Colors.black),
        centerTitle: false,
        backgroundColor: Colors.white,
        title: Text(
          'Astronaut Blog',
          style: TextStyle(
            color: Colors.black,
            fontSize: 26,
            fontWeight: FontWeight.bold,
          ),
        ),
        actions: [
          InkWell(
            child: MaterialButton(
              onPressed: () {
                print('Null');
              },
              child: CircleAvatar(
                backgroundImage: AssetImage('assets/images/niel.jpg'),
              ),
            ),
          ),
        ],
      ),
      bottomNavigationBar: ValueListenableBuilder(
          valueListenable: selectedIndex,
          builder: (context, int selected, _) {
            return BottomNavigationBar(
              backgroundColor: Colors.blueAccent,
              unselectedItemColor: Colors.white.withOpacity(0.5),
              selectedItemColor: Colors.white,
              currentIndex: selected,
              onTap: (index) {
                selectedIndex.value = index;
              },
              items: [
                BottomNavigationBarItem(
                    icon: Icon(Icons.supervised_user_circle_sharp),
                    label: 'Other Users'),
                BottomNavigationBarItem(
                    icon: Icon(Icons.person), label: 'My Logs')
              ],
            );
          }),
      body: SafeArea(
        minimum: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              decoration: InputDecoration(
                hintText: 'Search for logs, titles and tags',
                filled: true,
                fillColor: Colors.grey[200],
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.all(Radius.circular(10)),
                  borderSide: BorderSide.none,
                ),
                prefixIcon: Icon(Icons.search),
              ),
            ),
            const SizedBox(
              height: 20,
            ),
            ValueListenableBuilder(
              valueListenable: selectedIndex,
              builder: (context, int selected, _) {
                return tabs[selected];
              },
            ),
          ],
        ),
      ),
    );
  }
}
