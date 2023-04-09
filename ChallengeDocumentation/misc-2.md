| metadata                                  | <>                                     |
|-------------------------------------------|----------------------------------------|
| Developer Name(s)                         | kronicd & swalrey                      |
| Best Contact (Slack handle / Email address) | [redacted] |
| Challenge Category                        | Misc                                   |
| Challenge Tier                            | 2                                      |
| Challenge Type                            | Filedrop                          |

| Player facing         | <>                                                                                                                                                       |
|-----------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------|
| Challenge Name        | Spooky ham radio!|
| Challenge Description | It was late at night and Ham Radio enthusiast John was up late scanning the airwaves for interesting signals. He had been scanning for hours and was about to give up for the night when he suddenly came across a very strange signal. It was faint and distorted, but it seemed to be coming from a nearby house.|
|                       | It was late at night and I was scanning the airwaves for interesting signals. He had been scanning for hours and was about to give up for the night when he suddenly came across a very strange signal. It was faint and distorted, but it seemed to be from nearby.|
|                       | I quickly found myself out in the woods, stumbling around in the dark, following the signal to its source, and that's when I found the most bizarre sight I had ever seen.|
|                       | There was a small metal hut in a clearing, and it was surrounded by a ring of strange symbols that I didn't recognize. The hut was emitting the strange signal, and as I got closer, I could hear voices coming from inside.|
|                       | I cautiously approached the hut and peered inside. There were two people inside, both wearing strange headsets. They were speaking in a language I didn't understand, but the tone of their voices was urgent.|
|                       | I didn't know what to do, so I just stood there and watched as the two people communicated with each other. After a few minutes, they both turned to look at me, and I could see the fear in their eyes.|
|                       | I quickly backed away and ran back into the woods as fast as I could. I don't know what I saw that night, but it was something that I'll never forget.|
|                       | Decode the radio signals, get the flag. |
| Challenge Hint 1      | You will find out more if you look at the signal on a spectrograph and/or waterfall, it is quite narrow, about 300hz, you may want to zoom in!    |                                                                                           |
| Challenge Hint 2      | You need some sort of software to decode the signals, try common digital mode software in use by ham radio people on HF |
| Challenge Hint 3      | These signals can be difficult to align, being further to the left is often better |

| Admin Facing               | <>                                                                  |
|----------------------------|---------------------------------------------------------------------|
| Challenge Flag             | wactf{grand_central!hack*the*planet} |
| Challenge Vuln             | Its just some amateur packet radio stuff in a wav file              |
---

## Challenge PoC.py

### Decode

1. Load up WAV file in some software to view a waterfall/spectrograph
2. Observe text printed in the waterfall/spectrograph showing the encoding type and the words "key" and "data" followed by a series of weird tones
3. Google or otherwise understand the encoding types
4. Load up in your software of choice to decode the identified encoding, FLDIGI works pretty well for this
5. Select the RTTY 45 codec, align to the signal, and decode:

    ~~~
    KEY: C1E6B8F47C4415D52E930BBFB60275D1163128733D1BCDAEE124825386903A8C
    ... HOLD FOR DATA
    ~~~

6. Select the Olivia-4-125 codec, align to the signal (715 offset seems to work well, Olivia is tricky to align), and decode:

~~~
ciphertext: 4ZbZkBgte7IOs3ze1XYTqnFDSR1ZRK7Lj1DwMuqxUu2ijZKAFCE/pULyZdrCf1XxZlBMF1R1qo7BBA== 57 vk6hax <3
~~~

### Decrypt

1) Base64 decode
2) XOR against key
3) Get flag

### Cyberchef example

* Input

    ~~~
    4ZbZkBgte7IOs3ze1XYTqnFDSR1ZRK7Lj1DwMuqxUu2ijZKAFCE/pULyZdrCf1XxZlBMF1R1qo7BBA==
    ~~~

* Recipie

    ~~~
    From_Base64('A-Za-z0-9+/=',true,false)
    XOR({'option':'Hex','string':'c1e6b8f47c4415d52e930bbfb60275d1163128733d1bcdaee124825386903a8c'},'Standard',false)
    ~~~

* Output

    ~~~
     padding  wactf{grand_central!hack*the*planet}  padding   
    ~~~