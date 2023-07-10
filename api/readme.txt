API Functions

GetInfo
Parameters: id
Returns: The dress with the specified id (unique).
Example input:
abcd2/api/getinfo.php?id=1
Example output:
{"response_code":200,"message":"Dress found","data":{"id":1,"name":"Namasthe","description":"Namasthe is a customary, non-contact form of Hindu greeting. Namaste is usually spoken with a slight bow and hands pressed together, palms touching and fingers pointing upwards. Namaste is used as a form of greeting, acknowledging, and welcoming a relative, guest, or stranger. In some contexts, it can be used to express gratitude for assistance offered or given and to thank people for their generosity.\u00c2\u00a0","did_you_know":"The gesture of joining both hands has more than a symbolic meaning. It is said to provide a connection between the right and left hemispheres of the brain thus representing unification.","category":"people","type":"WOMAN","state_name":"all","key_words":"greeting, custom, symbolic, namasthe","image_url":"Slide1.PNG","status":"completed","notes":"Nikhitha"}}

Query
Parameters: type, category, keywords
Returns: The list of dresses which satisfy all of the specified criteria.
Example input:
abcd2/api/query.php?type=woman&category=regional
Example output:
{"response_code":200,"message":"Dress found","data":[{"id":10,"name":"Lambadi Dress","description":"The Lambadi dress, is worn by the tribal women from Rajasthan. There is a lehenga and blouse, and the dupatta is tucked into the lehenga, and begins to wrap like a saree, but only goes around about halfway and then is wrapped around the head. The designs of this dress consist of many small mirrors, and lots of colors. Like other dresses from Rajasthan, it is worn with lots of jewelry.\u00c2\u00a0","did_you_know":"The Lambadi or Banjara people originated from the deserts of Rajasthan, but have spread their tribes across India to the south.","category":"regional, tribal","type":"woman","state_name":"originating from Rajasthan, has spread to southern India","key_words":"Lambadi, banjara, tribal, lehenga, blouse, dupatta, mirrors, jewelry","image_url":"Slide10.PNG","status":"writeup_done\n\n","notes":"Ria"},{"id":227,"name":"Baiga Tribe (Woman)","description":"The Baiga are an ethnic group. They often decorate themselves in colorful manners using elements and materials from the forests they live in.","did_you_know":"Baiga people consider themselves as the heirs of the earth.","category":"regional","type":"woman","state_name":"Madhya Pradesh,\u00c2\u00a0Uttar Pradesh, Chhattisgarh and Jharkhand","key_words":"hats, turban","image_url":"Slide227.PNG","status":"writeup_done","notes":"Smriti"}]}