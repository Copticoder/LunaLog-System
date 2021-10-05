import 'package:flutter/material.dart';

class LogPostDetail extends StatefulWidget {
  final Map logDetails;
  LogPostDetail(this.logDetails);
  @override
  _LogPostDetailState createState() => _LogPostDetailState();
}

class _LogPostDetailState extends State<LogPostDetail> {
  bool playing = false;
  //
  Duration duration = Duration();
  Duration position = Duration();
  // // AudioPlayer audioPlayer = AudioPlayer();
  Widget returnWhich() {
    if (widget.logDetails['img_id'] != null) {
      return Container(
        child: ClipRRect(
          borderRadius: BorderRadius.circular(12),
          child: Image.network(
              'https://copticon.000webhostapp.com/image/${widget.logDetails['img_link']}'),
        ),
      );
    } else if (widget.logDetails['audio_id'] != null) {
      return Row(
        children: [
          Slider.adaptive(
              value: sliderValue,
              min: position.inSeconds.toDouble(),
              max: duration.inSeconds.toDouble(),
              onChanged: (double value) {
                setState(() {
                  // audioPlayer.seek(Duration(seconds: value.toInt()));
                });
              }),
          IconButton(
              onPressed: () {
                // playAudio();
              },
              icon: Icon(
                  playing == false
                      ? Icons.play_circle_fill
                      : Icons.pause_circle_filled,
                  size: 35,
                  color: Colors.blueAccent))
        ],
      );
    } else {
      return RichText(
        text: TextSpan(
          children: [
            TextSpan(
                text: '${widget.logDetails['log_data']}',
                style: TextStyle(
                    color: Colors.black,
                    fontSize: 18,
                    height: 1.7,
                    wordSpacing: 2)),
          ],
        ),
      );
    }
  }

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
                '${widget.logDetails['meta_data']}',
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
                  Text('Author: Me, Posted At: '),
                  Text(
                    '${widget.logDetails['posted_at']}',
                    style: TextStyle(color: Colors.grey),
                  ),
                ],
              ),
              const SizedBox(
                height: 20,
              ),
              const SizedBox(
                height: 20,
              ),
              returnWhich(),
            ],
          ),
        ),
      ),
    );
  }
}
