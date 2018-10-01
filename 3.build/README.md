# Data Preparation Process

#### MySQL Database Prepared
- Download the geonames_allCountries.txt file from geonames.org. This should be a 300+ meg tab-delimited file.
- Upload entire file directly to MySQL table named `geonames_allCountries`

---

#### All Countries (Admin0), Admin1, Admin2
Exported to this file: `gn_world_admin.csv`
```$xslt
SELECT * FROM geonames_allCountries 
WHERE feature_class = 'A' 
	AND ( 
		feature_code = 'ADM1' 
		OR feature_code = 'ADM2'
		OR feature_code = 'PCLI' 
		);
```


---

#### All Places for a Country
Exported to this file: `gn_us_p.csv`
```
SELECT * FROM geonames_allCountries 
WHERE feature_code != 'PPLF' 
	AND feature_code != 'PPLW' 
	AND feature_code != 'PPLCH'
	AND feature_code != 'PPLQ'
	AND feature_code != 'PPLR'
	AND feature_code != 'PPLCH'
	AND feature_class = 'P' 
	AND country_code = 'US'
```


#### Geonames Master Complete Records DB
This includes all admin areas, continents, and earth. Both as feature_class 'A' and 'P'.

```$xslt
SELECT * FROM dt_geonames 
WHERE 
	( feature_class = 'A' OR feature_class = 'P' OR feature_class = 'L' )
	AND ( feature_code LIKE 'ADM%' OR feature_code LIKE 'PLC%' OR feature_code LIKE 'PPLA%' OR feature_code = 'PPLC' OR feature_code = 'CONT' OR geonameid = '6295630' ) 
	AND feature_code NOT LIKE '%D' 
	AND feature_code NOT LIKE '%H'
```