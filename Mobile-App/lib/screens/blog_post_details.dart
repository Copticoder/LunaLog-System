import 'package:flutter/material.dart';

class BlogPostDetail extends StatefulWidget {
  final String title;
  final String image;
  final String author;
  final String date;

  BlogPostDetail(
      {required this.title,
      required this.image,
      required this.author,
      required this.date});
  @override
  _BlogPostDetailState createState() => _BlogPostDetailState();
}

class _BlogPostDetailState extends State<BlogPostDetail> {
  double sliderValue = 0;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: IconThemeData(
          color: Colors.black,
        ),
      ),
      body: SafeArea(
        minimum: const EdgeInsets.symmetric(horizontal: 16),
        child: Padding(
          padding: const EdgeInsets.only(top: 32),
          child: ListView(
            children: [
              Text(
                widget.title,
                style: TextStyle(
                  color: Colors.black,
                  fontSize: 32,
                ),
              ),
              const SizedBox(
                height: 16,
              ),
              Wrap(
                alignment: WrapAlignment.start,
                crossAxisAlignment: WrapCrossAlignment.center,
                children: [
                  CircleAvatar(
                    radius: 16,
                    backgroundImage: AssetImage('assets/images/niel.jpg'),
                  ),
                  const SizedBox(
                    width: 8,
                  ),
                  Text('${widget.author}, '),
                  Text(
                    widget.date,
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
              const SizedBox(
                height: 20,
              ),
              Container(
                child: ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Image.asset(widget.image),
                ),
              ),
              const SizedBox(
                height: 20,
              ),
              Row(
                children: [
                  Slider.adaptive(
                      value: sliderValue,
                      min: 0,
                      max: 100,
                      divisions: 100,
                      onChanged: (double value) {
                        setState(() {
                          sliderValue = value;
                        });
                      }),
                  IconButton(
                    onPressed: () {},
                    icon: Icon(Icons.play_circle_fill,
                        size: 35, color: Colors.blueAccent),
                  )
                ],
              ),
              // const SizedBox(
              //   height: 20,
              // ),
              RichText(
                text: TextSpan(
                  children: [
                    TextSpan(
                        text: 'A',
                        style: TextStyle(color: Colors.black, fontSize: 28)),
                    TextSpan(
                        text:
                            ' contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.',
                        style: TextStyle(
                            color: Colors.black,
                            fontSize: 18,
                            height: 1.7,
                            wordSpacing: 2)),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
