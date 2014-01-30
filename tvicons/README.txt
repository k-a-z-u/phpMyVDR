This folder contains logos for the tv channels which are displayed within
the GUI instead of the channels name.

As you might want to use one icon for several (similar) channels
a file named "iconToChannel.dat" is used to setup this mapping.

The format of this file is as follows:
	arte.png:arte
	arte hd.png:arte hd
	nickelodeon.png:nick/comedy;nick/comedy central

The logo "arte.png" is applied to all channels named "arte". The logo
"arte hd.png" to all channels named "arte hd". Finally, "nickelodeon.png"
is used for both, "nick/comedy" and "nick/comedy central". The case
of the channel name is ignored by using a lowercase characters here.


Note:
As I am not sure about licensing of those channel logos I will not commit them. 
